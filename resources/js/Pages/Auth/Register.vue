<script setup lang="ts">
import {ref} from "vue";
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const userData = useForm({
    first_name: '',
    second_name: '',
    last_name: ''
});

const form = useForm({
    id: null,
    email: '',
    password: '',
    password_confirmation: '',
});

const check_user = ref(true)

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const checkUser = () => {

    axios.post(route('user-check'), userData)
        .then(function (response) {
            if( response.data.status ) {
                form.id = response.data.data.user_id
                check_user.value = false
            }
        })
        .catch(function (error) {
            console.log(error);
        });

}
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form v-if="check_user" @submit.prevent="checkUser">

            <div>
                <InputLabel for="second_name" value="Фамилия" />

                <TextInput
                    id="second_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="userData.second_name"
                    required
                    autofocus
                    autocomplete="second_name"
                />

                <InputError class="mt-2" :message="userData.errors.second_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="first_name" value="Имя" />

                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="userData.first_name"
                    required
                    autocomplete="first_name"
                />

                <InputError class="mt-2" :message="userData.errors.first_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="last_name" value="Отчество" />

                <TextInput
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="userData.last_name"
                    autocomplete="last_name"
                />

                <InputError class="mt-2" :message="userData.errors.last_name" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Уже зарегистрированы?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': userData.processing }"
                    :disabled="userData.processing"
                >
                    Продолжить
                </PrimaryButton>
            </div>
        </form>

        <form v-if="!check_user" @submit.prevent="submit">

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Пароль" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Подтвердите пароль"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Уже зарегистрированы?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Зарегистрироваться
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
