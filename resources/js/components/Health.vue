<template>
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-end">
            <button class="btn btn-primary btn-sm btn-refresh ml-auto" :class="{loading: loading}"
                    @click="updateBackupStatuses" :disabled="! activeDisk || loading">
                <svg xmlns="http://www.w3.org/2000/svg" width="0.7875rem" height="0.7875rem" viewBox="0 0 24 24"
                     fill="currentColor">
                    <path class="heroicon-ui" d="M6 18.7V21a1 1 0 0 1-2 0v-5a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2H7.1A7 7 0 0 0 19 12a1 1 0 1 1 2 0 9 9 0 0 1-15 6.7zM18 5.3V3a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.9A7 7 0 0 0 5 12a1 1 0 1 1-2 0 9 9 0 0 1 15-6.7z"/>
                </svg>
            </button>
        </div>
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th scope="col">Disk</th>
                <th scope="col">Healthy</th>
                <th scope="col">Amount of backups</th>
                <th scope="col">Newest backup</th>
                <th scope="col">Used storage</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(backupStatus, index) in backupStatuses" :key="index">
                <td>{{ backupStatus.disk }}</td>
                <td>
                    <svg :is="backupStatus.healthy ? 'icon-healthy' : 'icon-unhealthy'" height="24px"/>
                </td>
                <td>{{ backupStatus.amount }}</td>
                <td>{{ backupStatus.newest }}</td>
                <td>{{ backupStatus.usedStorage }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import IconHealthy from './icons/IconHealthy';
    import IconUnhealthy from './icons/IconUnhealthy';

    export default {
        name: 'Health',

        components: {
            IconHealthy,
            IconUnhealthy,
        },

        data() {
            return {
                backupStatuses: [],
                loading: false,
            }
        },

        computed: {
            activeDisk() {
                return this.$store.state.activeDisk
            }
        },

        created() {
            this.updateBackupStatuses()
        },

        methods: {
            async updateBackupStatuses() {
                this.loading = true

                try {
                    const response = await this.$http.get('api/backup-statuses')

                    if (response.data.error) {
                        console.error(response.data.error)
                        return
                    }

                    this.backupStatuses = response.data

                    this.$store.commit('setDisks', this.backupStatuses.map(backupStatus => backupStatus.disk))

                    if (!this.activeDisk) {
                        this.$store.commit('setActiveDisk', this.backupStatuses[0].disk)
                    }
                } catch (e) {
                    console.error(e)
                }

                this.loading = false
            },
        }
    }
</script>

<style scoped>
    table thead th {
        text-transform: uppercase;
        font-size: 0.7rem;
        color: dimgrey;
        background-color: #f4f7fa;
        padding-top: 0.45rem;
        padding-bottom: 0.45rem;
        border-top: none;
        letter-spacing: 0.05rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f6fbff;
    }

    .card {
        border-radius: 0.45rem;
    }

    .card-header {
        background-color: #fff;
        padding: 0.75rem;
    }

    .card-header:first-child {
        border-radius: calc(0.45rem - 1px) calc(0.45rem - 1px) 0 0;
    }

    .btn {
        border-radius: 0.45rem;
        padding: 0.2rem 1.1rem;
    }

    .btn-refresh {
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .btn-refresh.loading svg {
        animation: loading-spinner 1s linear infinite;
    }

    @keyframes loading-spinner {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>
