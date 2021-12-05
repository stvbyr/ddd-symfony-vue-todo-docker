<template>
    <h1>Simple Todos</h1>
    <p>
        Choose which domain you wanna use. Both Options will consume the backend
        API. We will just expose the information that the UI needs to show (or
        other CRUD operations) the Modules.
    </p>
    <div class="modules">
        <router-link :to="{ name: 'todos' }">Todos</router-link>

        <router-link :to="{ name: 'habits' }">Habits</router-link>
    </div>
</template>

<script>
export default {};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.modules {
    display: flex;
    align-content: stretch;
    width: 100%;
    gap: 1rem;
}

.modules a {
    flex: 1;
    padding: 4rem;
    text-align: center;
    background: rgb(216, 216, 216);
}
</style>
