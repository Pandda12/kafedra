<script setup>
import { Bar } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    SubTitle
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, SubTitle)

// Props
// const props = defineProps({
//     salaries_for_graph: {
//         type: Object,
//         required: true
//     },
//     count: {
//         type: Number,
//         required: true
//     },
//     mean: {
//         type: Number,
//         required: true
//     },
//     median: {
//         type: Number,
//         required: true
//     }
// })

// Extract labels and values from the object
// const labels = Object.keys(props.salaries_for_graph)
// const values = Object.values(props.salaries_for_graph)

// Chart data
// const chartData = {
//     labels,
//     datasets: [
//         {
//             label: 'Количество зарплат',
//             data: values,
//             backgroundColor: 'rgba(75, 192, 192, 0.6)',
//             borderColor: 'rgba(75, 192, 192, 1)',
//             borderWidth: 1
//         }
//     ]
// }

// const labels = Utils.months({count: 7});

// const data = {
//     labels: [1,2,3,4,5,6,7, 7],
//     grouped: true,
//     datasets: [{
//         axis: 'y',
//         label: 'My First Dataset',
//         data: [65, 59, 80, 81, 56, 55, 21, 21],
//         // data: [65, 59, 80, 81, 56, 55, 40, 21],
//         // series: [{
//         //     data: [44, 55, 41, 64, 22, 43, 21]
//         // }, {
//         //     data: [53, 32, 33, 52, 13, 44, 32]
//         // }],
//         fill: false,
//
//         backgroundColor: [
//             'rgba(255, 99, 132, 0.2)',
//             'rgba(255, 159, 64, 0.2)',
//             'rgba(255, 205, 86, 0.2)',
//             'rgba(75, 192, 192, 0.2)',
//             'rgba(54, 162, 235, 0.2)',
//             'rgba(153, 102, 255, 0.2)',
//             'rgba(201, 203, 207, 0.2)'
//         ],
//         borderColor: [
//             'rgb(255, 99, 132)',
//             'rgb(255, 159, 64)',
//             'rgb(255, 205, 86)',
//             'rgb(75, 192, 192)',
//             'rgb(54, 162, 235)',
//             'rgb(153, 102, 255)',
//             'rgb(201, 203, 207)'
//         ],
//         borderWidth: 1
//     }]
// }

const data = {
    labels: [
        "Absence of OB",
        "Closeness",
        "Credibility",
        "Heritage",
        "M Disclosure",
        "Provenance",
        "Reliability",
        "Transparency"
    ],
    datasets: [
        {
            label: "American Express",
            backgroundColor: "pink",
            borderColor: "red",
            borderWidth: 1,
            data: [3, 5, 6, 7,3, 5, 6, 7]
        },
        {
            label: "Mastercard",
            backgroundColor: "lightblue",
            borderColor: "blue",
            borderWidth: 1,
            data: [4, 7, 3, 6, 10,7,4,6]
        },

    ]
}

// Chart options
// const chartOptions = {
//     responsive: true,
//     // responsive: false,
//     maintainAspectRatio: false,
//     plugins: {
//         legend: {
//             display: false
//         },
//         title: {
//             display: true,
//             text: `Среднее значение: ${props.mean}, Медиана: ${props.median}, Всего учтено: ${props.count}`,
//             font: {
//                 size: 18
//             },
//             padding: {
//                 bottom: 10
//             }
//         },
//         subtitle: {
//             display: true,
//             text: 'Пересчитано в BYN',
//             font: {
//                 size: 14
//             },
//             padding: {
//                 top: 0,
//                 bottom: 10
//             },
//             color: '#666'
//         }
//     },
//     scales: {
//         x: {
//             ticks: {
//                 autoSkip: false,
//                 maxRotation: 90,
//                 minRotation: 45
//             }
//         }
//     }
// }

// const options = {
//     // responsive: true,
//     responsive: false,
//     maintainAspectRatio: false,
//     indexAxis: 'y',
//     scales: {
//         x: {
//             stacked: true,
//         },
//         y: {
//             stacked: true
//         }
//     }
// }

const options = {
    indexAxis: 'y',
    // Elements options apply to all of the options unless overridden in a dataset
    // In this case, we are setting the border of each horizontal bar to be 2px wide
    elements: {
        bar: {
            borderWidth: 2,
        }
    },
    responsive: true,
    plugins: {
        legend: {
            position: 'right',
        },
        title: {
            display: true,
            text: 'Chart.js Horizontal Bar Chart'
        },
    }

}

const styles = {
    width: '100%',
    // width: '1000px',
    height: '400px'
}
</script>

<template>
    <div>
        <Bar :data="data" :options="options" :style="styles" />
    </div>
</template>