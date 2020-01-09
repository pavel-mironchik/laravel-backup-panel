<template>
    <div class="container mb-5">
        <div class="d-flex align-items-end pt-4">
            <h5 class="mb-0">
                Laravel Backup Panel
            </h5>

            <button @click="createBackup('')" class="btn btn-primary btn-sm ml-auto px-3">
                Create Backup
            </button>
            <div class="dropdown ml-3">
                <button class="btn btn-primary btn-sm dropdown-toggle px-3" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="0.7875rem" height="0.7875rem" viewBox="0 0 24 24"
                         fill="currentColor">
                        <path class="heroicon-ui" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/>
                    </svg>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" @click.prevent="createBackup('only-db')">
                        Create database backup
                    </a>
                    <a class="dropdown-item" href="#" @click.prevent="createBackup('only-files')">
                        Create files backup
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <router-view/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'App',

        methods: {
            async createBackup(option = '') {
                try {
                    const msg = 'Creating a new backup in the background...' + (option ? ' (' + option + ')' : '')
                    this.$toasted.show(msg, {
                        theme: 'toasted-primary',
                        position: 'bottom-right',
                        duration : 5000,
                        type: 'success',
                    });

                    const response = await this.$http.post('api/backups', { option })

                    if (response.data.error) {
                        console.error(response.data.error)
                    }
                } catch (e) {
                    console.error(e)
                }
            }
        }
    }
</script>

<style scoped>
    button {
        border-radius: 0.45rem;
        font-weight: bold;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .dropdown-menu {
        padding: 0;
        border-radius: 0.45rem;
    }

    .dropdown-menu a:first-of-type {
        border-radius: 0.45rem 0.45rem 0 0;
    }

    .dropdown-menu a:last-of-type {
        border-radius: 0 0 0.45rem 0.45rem;
    }
</style>

<style>
    .toasted-container .toasted.toasted-primary.success {
        border-radius: 0.45rem;
        min-height: 30px;
        background-color: #1fb16e;
        font-size: 14px;
    }
</style>
