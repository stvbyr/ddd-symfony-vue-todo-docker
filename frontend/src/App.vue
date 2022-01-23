<template>
    <router-view></router-view>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    props: {},
    setup(props) {},
});
</script>

<style>
* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
body {
    padding: 2rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 800px;
    margin: 0 auto;
}
h1,
h2,
h3,
p {
    margin-bottom: 1rem;
}
</style>
