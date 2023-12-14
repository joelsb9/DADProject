<script>
export default {
    name: 'Profile',
    // Add your component logic here
}
</script>

<template>
    <div class="home">
        <div class="main-content">
            <h1>Welcome to the Profile Page</h1>
        </div>
    </div>
</template>

<style scoped>
.home {
    display: flex;
}

.sidebar h2 {
    margin: 0;
    color: #bdc3c7;
}

.main-content {
    flex-grow: 1;
    padding: 20px;
    background-color: #ecf0f1;
}

.main-content h1 {
    color: #2c3e50;
}
</style>