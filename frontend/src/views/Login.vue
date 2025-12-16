<script setup>
    import { ref } from 'vue';
    import api from '../api/axios';
    import { useRouter } from 'vue-router';

    const router = useRouter();
    const email = ref('');
    const password = ref('');

    const login = async () => {
        await api.get('/sanctum/csrf-cookie');
        await api.post('/api/login', {
            email: email.value,
            password: password.value,
        });
        router.push('/tasks');
    };
</script>

<template>
    <div>
        <h1>ログイン</h1>
        <input v-model="email" placeholder="email"><br>
        <input type="password" v-model="password" placeholder="password"><br>
        <button @click="login"></button>
    </div>
</template>