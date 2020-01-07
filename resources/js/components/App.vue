<template>
    <div class="container mb-5">
        <div class="d-flex align-items-center py-4 header">
            <h4 class="mb-0 ml-2">
                <strong>Laravel Backup Dashboard</strong>{{ appName ? ' - ' + appName : '' }}
            </h4>

            <button class="btn btn-outline-secondary ml-auto" @click="backup">
                <svg class="bi bi-archive" width="1.5em" height="1.5em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 7v7.5c0 .864.642 1.5 1.357 1.5h9.286c.715 0 1.357-.636 1.357-1.5V7h1v7.5c0 1.345-1.021 2.5-2.357 2.5H5.357C4.021 17 3 15.845 3 14.5V7h1z" clip-rule="evenodd"></path>
                    <path fill-rule="evenodd" d="M7.5 9.5A.5.5 0 018 9h4a.5.5 0 010 1H8a.5.5 0 01-.5-.5zM17 4H3v2h14V4zM3 3a1 1 0 00-1 1v2a1 1 0 001 1h14a1 1 0 001-1V4a1 1 0 00-1-1H3z" clip-rule="evenodd"></path>
                </svg>
                Backup now
            </button>
        </div>

        <div class="row mt-4">
            <div class="col-2">
                <navigation/>
            </div>

            <div class="col-10">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import Navigation from "./Navigation"

    export default {
        name: 'App',

        components: {
            Navigation
        },

        data() {
            return {
                appName: window.LaravelBackupPanel.appName
            }
        },

        methods: {
            async backup() {
                try {
                    const response = await this.$http.post(`/${LaravelBackupPanel.path}/api/files`)

                    if (response.data.error) {
                        console.error(response.data.error)
                        return
                    }

                    this.$eventHub.$emit('backup-created')
                } catch (e) {
                    console.error(e)
                }
            }
        }
    }
</script>

<style scoped>
    .header {
        border-bottom: 1px solid #d6dce9;
    }

    button svg {
        margin-right: 5px;
    }
</style>
