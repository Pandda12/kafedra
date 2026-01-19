<template>
    <div class="gantt-wrapper">
        <!-- Верхняя шкала (недели/дни) -->
        <div class="gantt-header" :style="{ marginLeft: sidebarWidth + 'px' }">
            <div
                v-for="tick in headerTicks"
                :key="tick.key"
                class="gantt-header-cell"
                :class="{ major: tick.major }"
                :style="{ left: tick.x + 'px', width: tick.w + 'px' }"
                :title="tick.title"
            >
                {{ tick.label }}
            </div>
        </div>

        <div class="gantt-body">
            <!-- Левая часть: «группировка как в Chart.js» (группы — секции с серым фоном, как category-группы) -->
            <div class="gantt-sidebar" :style="{ width: sidebarWidth + 'px' }">
                <template v-for="section in groupedRows" :key="section.group">
                    <div class="sidebar-section-head">
                        <strong>{{ section.group }}</strong>
                        <span class="muted">({{ section.items.length }})</span>
                    </div>
                    <div
                        v-for="task in section.items"
                        :key="task.id"
                        class="sidebar-row"
                        @click="$emit('task-click', task)"
                    >
                        <span class="dot"></span>
                        <span class="task-name" :title="task.name">{{ task.name }}</span>
                    </div>
                </template>
            </div>

            <!-- Правая часть: диаграмма -->
            <div class="gantt-chart" ref="chartRef">
                <!-- фон секций (как групповые дорожки у Chart.js category scale) -->
                <template v-for="bg in sectionBackgrounds" :key="bg.key">
                    <div class="section-bg" :style="{ top: bg.top + 'px', height: bg.height + 'px' }"></div>
                </template>

                <!-- вертикальная сетка -->
                <div
                    v-for="grid in gridLines"
                    :key="grid.x"
                    class="v-grid"
                    :class="{ major: grid.major }"
                    :style="{ left: grid.x + 'px' }"
                ></div>

                <!-- линия "сегодня" -->
                <div v-if="showToday" class="today-line" :style="{ left: xToday + 'px' }"></div>

                <!-- бары задач -->
                <div
                    v-for="bar in bars"
                    :key="bar.id"
                    class="bar"
                    :class="bar.statusClass"
                    :style="{ left: bar.x + 'px', width: bar.w + 'px', top: bar.y + 'px' }"
                    :title="bar.tooltip"
                    @click="$emit('task-click', bar.task)"
                >
                    <span class="bar-label">{{ bar.task.name }}</span>
                </div>

                <!-- пунктирные линии от конца задач вниз -->
                <div
                    v-for="endL in endGuides"
                    :key="endL.key"
                    class="end-guide"
                    :style="{ left: endL.x + 'px', top: endL.top + 'px', height: endL.h + 'px' }"
                ></div>

                <!-- горизонтальные разделители строк -->
                <div v-for="sep in rowSeparators" :key="sep" class="h-sep" :style="{ top: sep + 'px' }"></div>

                <!-- нижняя шкала по датам завершения -->
                <div class="gantt-footer">
                    <div
                        v-for="tick in bottomEndTicks"
                        :key="tick.key"
                        class="footer-tick"
                        :style="{ left: tick.x + 'px' }"
                        :title="tick.title"
                    >
                        <div class="tick-mark"></div>
                        <div class="tick-label">{{ tick.label }}</div>
                    </div>
                </div>

                <!-- наполнитель, чтобы последний ряд был видим -->
                <div :style="{ height: contentHeight + footerHeight + 'px' }"></div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'

type TaskInput = {
    id: string | number
    name: string
    start: string | Date
    end: string | Date
    group?: string
    status?: 'open' | 'in_progress' | 'review' | 'done' | 'blocked'
}

const props = withDefaults(defineProps<{
    tasks: TaskInput[]
    startDate?: string | Date
    endDate?: string | Date
    /** Масштаб в днях: 1..365 (сколько календарных дней умещаем на «базовую ширину») */
    scaleDays?: number
    rowHeight?: number
    rowGap?: number
    sidebarWidth?: number
    showToday?: boolean
}>(), {
    scaleDays: 30,
    rowHeight: 28,
    rowGap: 6,
    sidebarWidth: 280,
    showToday: true,
})

const chartRef = ref<HTMLElement | null>(null)
const footerHeight = 42

/** dayWidth считается обратно пропорционально scaleDays, чтобы scaleDays=1 → крупный зум, 365 → мелкий */
const dayWidth = computed(() => {
    const clamped = Math.min(365, Math.max(1, Math.round(props.scaleDays!)))
    const baseFor30 = 28 // ширина дня при scaleDays=30
    return Math.max(1, Math.round(baseFor30 * (30 / clamped)))
})

const parsed = computed(() =>
    props.tasks.map(t => ({ ...t, start: new Date(t.start), end: new Date(t.end), group: t.group ?? 'Без группы' }))
)

const minDate = computed(() => props.startDate ? new Date(props.startDate) :
    new Date(Math.min(...parsed.value.map(t => t.start.getTime()))))

const maxDate = computed(() => props.endDate ? new Date(props.endDate) :
    new Date(Math.max(...parsed.value.map(t => t.end.getTime()))))

function daysBetween(a: Date, b: Date) { return Math.ceil((b.getTime() - a.getTime()) / 86400000) }
const totalDays = computed(() => Math.max(1, daysBetween(minDate.value, maxDate.value)))

/* группировка «как в Chart.js»: визуально секции по category с общим фоном и заголовком */
const groupedRows = computed(() => {
    const map = new Map<string, TaskInput[]>()
    for (const t of parsed.value) {
        if (!map.has(t.group!)) map.set(t.group!, [])
        map.get(t.group!)!.push(t)
    }
    // внутри группы — по старту
    return Array.from(map.entries())
        .sort((a,b)=>a[0].localeCompare(b[0]))
        .map(([group, items]) => ({ group, items: items.sort((a,b)=>+a.start - +b.start) }))
})

const rowsFlat = computed(() =>
    groupedRows.value.flatMap(sec => sec.items.map(task => ({ group: sec.group, task })))
)

const contentHeight = computed(() =>
    rowsFlat.value.length * (props.rowHeight + props.rowGap)
)

const xForDate = (d: Date) => Math.max(0, daysBetween(minDate.value, d) * dayWidth.value)
const widthForRange = (a: Date, b: Date) => Math.max(2, daysBetween(a, b) * dayWidth.value)

const bars = computed(() => {
    const out: { id: string|number, x: number, w: number, y: number, task: TaskInput, tooltip: string, statusClass: string }[] = []
    rowsFlat.value.forEach((row, idx) => {
        const x = xForDate(row.task.start as Date)
        const w = widthForRange(row.task.start as Date, row.task.end as Date)
        const y = idx * (props.rowHeight + props.rowGap)
        const tooltip = `${row.task.name}\n${(row.task.start as Date).toDateString()} — ${(row.task.end as Date).toDateString()}`
        out.push({
            id: row.task.id, x, w, y, task: row.task,
            tooltip,
            statusClass: row.task.status ? `status-${row.task.status}` : 'status-open'
        })
    })
    return out
})

const rowSeparators = computed(() => {
    const arr: number[] = []
    rowsFlat.value.forEach((_, idx) => {
        arr.push((idx + 1) * (props.rowHeight + props.rowGap) - props.rowGap / 2)
    })
    return arr
})

/* фоны секций (как «категории» у Chart.js): на всю ширину, по высоте набора задач */
const sectionBackgrounds = computed(() => {
    const res: { key: string, top: number, height: number }[] = []
    let cursor = 0
    for (const sec of groupedRows.value) {
        const h = sec.items.length * (props.rowHeight + props.rowGap)
        res.push({ key: sec.group, top: cursor, height: h })
        cursor += h
    }
    return res
})

/* сетка: дни (minor) и понедельники (major) */
const gridLines = computed(() => {
    const start = new Date(minDate.value)
    const end = new Date(maxDate.value)
    const lines: { x: number, major: boolean }[] = []
    const c = new Date(start)
    while (c <= end) {
        lines.push({ x: xForDate(c), major: c.getDay() === 1 })
        c.setDate(c.getDate() + 1)
    }
    return lines
})

/* верхние тики: неделями */
function weekNumber(d: Date) {
    const date = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()))
    const dayNum = date.getUTCDay() || 7
    date.setUTCDate(date.getUTCDate() + 4 - dayNum)
    const yearStart = new Date(Date.UTC(date.getUTCFullYear(),0,1))
    return Math.ceil((((date as any) - (yearStart as any)) / 86400000 + 1) / 7)
}
const headerTicks = computed(() => {
    const res: { key: string, x: number, w: number, label: string, title: string, major: boolean }[] = []
    const start = new Date(minDate.value)
    const cursor = new Date(start)
    // выравниваем к понедельнику
    const deltaToMon = (cursor.getDay() + 6) % 7
    cursor.setDate(cursor.getDate() - deltaToMon)
    while (cursor < maxDate.value) {
        const periodStart = new Date(cursor)
        const periodEnd = new Date(cursor); periodEnd.setDate(periodEnd.getDate() + 7)
        const x = xForDate(periodStart)
        const w = widthForRange(periodStart, periodEnd)
        const wn = weekNumber(periodStart)
        res.push({
            key: periodStart.toISOString(),
            x, w,
            label: `Нед ${wn}`,
            title: `${periodStart.toISOString().slice(0,10)} — ${periodEnd.toISOString().slice(0,10)}`,
            major: true
        })
        cursor.setDate(cursor.getDate() + 7)
    }
    return res
})

/* нижняя шкала: уникальные даты завершения задач */
const bottomEndTicks = computed(() => {
    const uniq = new Map<string, Date>()
    parsed.value.forEach(t => {
        const d = new Date(t.end)
        const key = d.toISOString().slice(0,10)
        if (!uniq.has(key)) uniq.set(key, d)
    })
    return Array.from(uniq.values())
        .sort((a,b)=>+a - +b)
        .map(d => ({
            key: d.toISOString(),
            x: xForDate(d),
            label: d.toISOString().slice(0,10),
            title: d.toDateString()
        }))
})

/* пунктир от конца каждой задачи к нижней шкале */
const endGuides = computed(() => {
    const topOfFooter = contentHeight.value + 6 // чуть выше подписи
    return parsed.value.map((t, idx) => {
        const x = xForDate(new Date(t.end))
        // y = нижний край соответствующего бара
        const barIndex = rowsFlat.value.findIndex(r => r.task.id === t.id)
        const barTop = barIndex * (props.rowHeight + props.rowGap)
        const top = barTop + 24 // бар 24px
        const height = Math.max(0, topOfFooter - top)
        return { key: `${t.id}-${idx}`, x, top, h: height }
    })
})

/* today line */
const xToday = computed(() => xForDate(new Date()))

onMounted(() => {
    if (chartRef.value) chartRef.value.scrollLeft = Math.max(0, xToday.value - 200)
})

watch(() => props.scaleDays, () => {
    // при смене масштаба мягко центрируем «сегодня»
    if (chartRef.value) chartRef.value.scrollLeft = Math.max(0, xToday.value - 200)
})
</script>

<style scoped>
.gantt-wrapper { position: relative; border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; overflow: hidden; }
.gantt-header { position: sticky; top: 0; z-index: 3; height: 36px; background: #fafafa; border-bottom: 1px solid #eee; overflow: hidden; }
.gantt-header-cell { position: absolute; top: 0; bottom: 0; display: flex; align-items: center; padding-left: 6px; font-size: 12px; white-space: nowrap; border-left: 1px solid #eee; }
.gantt-header-cell.major { background: #f9fafb; }

.gantt-body { display: flex; max-height: 560px; overflow: hidden; }
.gantt-sidebar { flex: 0 0 auto; border-right: 1px solid #eee; background: #fcfcfc; overflow-y: auto; max-height: 560px; }
.sidebar-section-head { position: sticky; top: 0; padding: 8px 10px; background: #f3f4f6; border-bottom: 1px solid #e5e7eb; z-index: 1; }
.sidebar-section-head + .sidebar-row { border-top: none; }
.sidebar-row { display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-bottom: 1px solid #f3f4f6; cursor: pointer; background: #fff; }
.sidebar-row .muted { color: #6b7280; font-size: 12px; margin-left: 6px; }
.sidebar-row .dot { width: 8px; height: 8px; border-radius: 50%; background: #9ca3af; }
.sidebar-row .task-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.gantt-chart { position: relative; flex: 1 1 auto; overflow: auto; max-height: 560px; }
.section-bg { position: absolute; left: 0; right: 0; background: #fafafa; }
.v-grid { position: absolute; top: 0; bottom: 0; width: 1px; background: #f0f0f0; }
.v-grid.major { background: #e1e1e1; }

.today-line { position: absolute; top: 0; bottom: 0; width: 2px; background: #f59e0b; z-index: 2; }

.bar { position: absolute; height: 24px; border-radius: 6px; display: flex; align-items: center; padding: 0 8px; box-shadow: 0 1px 0 rgba(0,0,0,0.05) inset; background: #e5e7eb; border: 1px solid #d1d5db; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bar .bar-label { font-size: 12px; }
.status-open        { background: #e5e7eb; border-color: #d1d5db; }
.status-in_progress { background: #dbeafe; border-color: #bfdbfe; }
.status-review      { background: #ede9fe; border-color: #ddd6fe; }
.status-done        { background: #dcfce7; border-color: #bbf7d0; }
.status-blocked     { background: #fee2e2; border-color: #fecaca; }

.end-guide { position: absolute; width: 0; border-left: 1px dashed #9ca3af; }

.h-sep { position: absolute; left: 0; right: 0; height: 1px; background: #f1f5f9; }

.gantt-footer { position: absolute; left: 0; right: 0; height: 42px; bottom: 0; border-top: 1px solid #eee; background: #fafafa; }
.footer-tick { position: absolute; bottom: 0; transform: translateX(-50%); text-align: center; font-size: 11px; }
.footer-tick .tick-mark { width: 1px; height: 8px; background: #9ca3af; margin: 0 auto 2px; }
.footer-tick .tick-label { white-space: nowrap; }
</style>
