<template>
    <div>
        <health class="mb-4"/>
        <files/>
    </div>
</template>

<script>
    import Files from "../Files"
    import Health from "../Health"

    export default {
        name: "BackupsScreen",

        components: {
            Files,
            Health
        }
    }
</script>