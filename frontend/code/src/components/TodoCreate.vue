<template>
    <h1>Create: {{ title || 'New Todo' }}</h1>
    <form>
        <div>
            <label for="title">Title</label>
            <input type="text" v-model="title" id="title" />
        </div>
        <div>
            <label for="date">Date</label>
            <input type="date" v-model="date" id="date" />
        </div>
        <div>
            <button type="button" @click="submit">Add Todo</button>
        </div>
    </form>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import todosApi from './../api/Todos';

const formatDate = (date: Date = new Date()) =>
    `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;

const title = ref('');
const date = ref(formatDate());

const submit = () => {
    todosApi.createTodo({
        title: title.value,
        date: date.value,
    });
};

export default defineComponent({
    setup() {
        return { title, date, submit };
    },
});
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
