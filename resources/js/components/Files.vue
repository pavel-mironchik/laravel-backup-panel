<template>
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-end">
            <div v-if="disks.length > 1" class="btn-group btn-group-toggle" data-toggle="buttons">
                <label v-for="disk in disks" :key="disk" class="btn btn-outline-secondary"
                       :class="{active: activeDisk === disk}" @click="updateActiveDisk(disk)">
                    <input type="radio" name="options" :checked="activeDisk === disk">
                    {{ disk }}
                </label>
            </div>

            <button class="btn btn-primary btn-sm btn-refresh ml-auto" :class="{loading: loading}" @click="getFiles"
                    :disabled="! activeDisk || loading">
                <svg xmlns="http://www.w3.org/2000/svg" width="0.7875rem" height="0.7875rem" viewBox="0 0 24 24"
                     fill="currentColor">
                    <path class="heroicon-ui" d="M6 18.7V21a1 1 0 0 1-2 0v-5a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2H7.1A7 7 0 0 0 19 12a1 1 0 1 1 2 0 9 9 0 0 1-15 6.7zM18 5.3V3a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.9A7 7 0 0 0 5 12a1 1 0 1 1-2 0 9 9 0 0 1 15-6.7z"/>
                </svg>
            </button>
        </div>

        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col">Path</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Size</th>
                    <th scope="col"/>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(file, index) in files" :key="index">
                    <td>{{ file.path }}</td>
                    <td>{{ file.date }}</td>
                    <td>{{ file.size }}</td>
                    <td class="text-right pr-3">
                        <a class="mr-2" :href="`/${apiPath}/api/download-backup?disk=${activeDisk}&path=${file.path}`"
                           target="_blank" rel="noopener nofollow">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path class="heroicon-ui" d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"/>
                            </svg>
                        </a>
                        <a href="#" target="_blank" @click.prevent="showDeleteModal(file)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path class="heroicon-ui" d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                <tr v-if="files.length === 0">
                    <td class="text-center" colspan="4">
                        {{ 'No backups present' }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="modal-title mb-3">Delete backup</h5>
                        <span class="text-muted" v-if="deletingFile">
                            Are you sure you want to delete the backup created at {{ deletingFile.date }} ?
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary cancel-button" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger delete-button" @click="deleteFile">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Files',

        data() {
            return {
                apiPath: LaravelBackupPanel.path,
                files: [],
                deletingFile: null,
                loading: false,
            }
        },

        computed: {
            activeDisk() {
                return this.$store.state.activeDisk
            },

            disks() {
                return this.$store.state.disks
            }
        },

        watch: {
            activeDisk() {
                this.getFiles()
            }
        },

        mounted() {
            $('#deleteModal').on('hidden.bs.modal', () => {
                this.deletingFile = null
            })
        },

        methods: {
            async getFiles() {
                if (!this.activeDisk) {
                    return;
                }

                this.loading = true

                try {
                    const response = await this.$http.get(`api/backups?disk=${this.activeDisk}`)

                    if (response.data.error) {
                        console.error(response.data.error)
                        return
                    }

                    this.files = response.data
                } catch (e) {
                    console.error(e)
                }

                this.loading = false
            },

            async deleteFile() {
                try {
                    const response = await this.$http
                        .delete(`api/backups?disk=${this.activeDisk}&path=${this.deletingFile.path}`)

                    if (response.data.error) {
                        console.error(response.data.error)
                        return
                    }

                    const index = this.files.indexOf(this.deletingFile)
                    this.files.splice(index, 1)
                } catch (e) {
                    console.error(e)
                }

                $('#deleteModal').modal('hide')
            },

            showDeleteModal(file) {
                this.deletingFile = file

                $('#deleteModal').modal('show')
            },

            updateActiveDisk(disk) {
                this.$store.commit('setActiveDisk', disk)
            }
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

    td svg {
        fill: dimgrey;
        fill-opacity: 0.3;
    }

    td svg:hover {
        fill: #3c86cc;
        fill-opacity: 0.7;
    }

    .btn {
        border-radius: 0.45rem;
        padding: 0.2rem 1.1rem;
    }

    .cancel-button {
        border: none;
    }

    .delete-button {
        font-weight: bold;
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

    .modal-content {
        border-radius: 0.45rem;
        border-width: 0;
    }

    .modal-body {
        padding: 1.25rem;
    }

    .modal-footer {
        border-top: none;
        background-color: #f3f7fa;
        padding: 0.5rem 1rem;
        border-bottom-right-radius: 0.45rem;
        border-bottom-left-radius: 0.45rem;
    }
</style>
