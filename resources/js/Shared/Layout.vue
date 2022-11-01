<template>
    <head>
        <meta property="og:title" content="Warnock Variety Show"/>
        <title>Warnock Variety Show</title>
    </head>
    <div class="container">
        <header>
            <h1 class="text-center my-3 site-title"><a href="/">Warnock Variety Show</a></h1>
            <Nav />
        </header>
        <slot />
    </div>
</template>

<script>
import Nav from "../Layout/Nav";
export default {
    name: "Layout",
    components: {Nav}
}
</script>

<style scoped>

</style>
