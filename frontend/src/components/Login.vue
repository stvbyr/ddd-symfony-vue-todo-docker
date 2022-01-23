<template>
    <div class="hello">
        <h1>Login</h1>
        <form>
            <div>
                <label for="username">Username</label
                ><input v-model="username" />
            </div>
            <div>
                <label for="password">Password></label
                ><input type="password" v-model="password" />
            </div>
            <div>
                <button type="button" @click="login">Login</button>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import loginApi from './../api/Login';

const username = ref('');
const password = ref('');

const login = async () => {
    const result = await loginApi.fetchToken({
        username: username.value,
        password: password.value,
    });
    console.log(result);
};

export default defineComponent({
    props: {},
    setup() {
        return { username, password, login };
    },
});
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
