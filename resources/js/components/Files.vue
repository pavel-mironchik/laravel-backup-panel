<template>
    <div class="card">
        <div class="card-header">Files</div>

        <div class="card-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Disk</th>
                    <th scope="col">Name</th>
                    <th scope="col">Size</th>
                    <th scope="col">Date</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(file, index) in files" :key="index">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ file.disk }}</td>
                    <td>{{ file.name }}</td>
                    <td>{{ file.size }}</td>
                    <td>{{ file.date }}</td>
                    <td>
                        <a class="btn btn-success btn-sm" v-if="file.download" target="_blank"
                           :href="`api/files/download?disk=${file.disk}&path=${file.path}`">
                            <svg class="bi bi-download" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.5 10a.5.5 0 01.5.5V14a1 1 0 001 1h12a1 1 0 001-1v-3.5a.5.5 0 011 0V14a2 2 0 01-2 2H4a2 2 0 01-2-2v-3.5a.5.5 0 01.5-.5z" clip-rule="evenodd"></path>
                                <path fill-rule="evenodd" d="M7 9.5a.5.5 0 01.707 0L10 11.793 12.293 9.5a.5.5 0 01.707.707l-2.646 2.647a.5.5 0 01-.708 0L7 10.207A.5.5 0 017 9.5z" clip-rule="evenodd"></path>
                                <path fill-rule="evenodd" d="M10 3a.5.5 0 01.5.5v8a.5.5 0 01-1 0v-8A.5.5 0 0110 3z" clip-rule="evenodd"></path>
                            </svg>
                            Download
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" @click="remove(file)">
                            <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 7.5A.5.5 0 018 8v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V8z"></path>
                                <path fill-rule="evenodd" d="M16.5 5a1 1 0 01-1 1H15v9a2 2 0 01-2 2H7a2 2 0 01-2-2V6h-.5a1 1 0 01-1-1V4a1 1 0 011-1H8a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM6.118 6L6 6.059V15a1 1 0 001 1h6a1 1 0 001-1V6.059L13.882 6H6.118zM4.5 5V4h11v1h-11z" clip-rule="evenodd"></path>
                            </svg>
                            Delete
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Files',

        data() {
            return {
                files: []
            }
        },

        mounted() {
            this.getFiles()

            this.$eventHub.$on('backup-created', this.getFiles)
        },

        beforeDestroy() {
            this.$eventHub.$off('backup-created')
        },

        methods: {
            async getFiles() {
                try {
                    const response = await this.$http.get(`/${LaravelBackupPanel.path}/api/files`)

                    if (response.data.error) {
                        console.error(response.data.error)
                        return
                    }

                    this.files = response.data.files
                } catch (e) {
                    console.error(e)
                }
            },

            async remove(file) {
                try {
                    const response = await this.$http.delete(`/${LaravelBackupPanel.path}/api/files?disk=${file.disk}&path=${file.path}`)

                    if (response.data.error) {
                        console.error(response.data.error)
                        return
                    }

                    this.getFiles()
                } catch (e) {
                    console.error(e)
                }
            }
        }
    }
</script>
