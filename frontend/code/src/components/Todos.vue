<template>
    <div class="hello">
        <h1>Todos</h1>
        <router-link :to="{ name: 'todo.create' }">🆕 New Todo </router-link>
        <ul>
            <li v-for="todo in todos" :key="todo.uuid">{todo.title}</li>
        </ul>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import todosApi from './../api/Todos';

const todos = async () => {
    return await todosApi.fetchAllTodos();
};

export default defineComponent({
    props: {},
    setup(props) {
        return {
            todos,
        };
    },
});
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
