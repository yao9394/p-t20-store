<template>
    <div class="row justify-content-center align-items-center">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" v-for="key in columns" :key="key">
                        {{key}}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="file in myFiles" :key="file.id">
                    <td>{{file.file_name}}</td>
                    <td>{{file.order_at}}</td>
                    <td><button class="btn btn-primary" v-on:click="downloadCsv(file)">Download</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    created() {
        this.fetchFiles()
    },
    data() {
        return {
            myFiles: [],
            columns: ['File name', 'Ordered at', 'Actions'],
        }
    },
    methods: {
        fetchFiles() {
            axios.get('api/files').then(response => {
                this.myFiles = response.data
            }).catch(error => console.log(error));
        },
        downloadCsv(file) {
            const config = {
                headers: {
                            "content-type": "multipart/form-data",
                            "Access-Control-Allow-Origin": "*"
                            }
                };
            axios.get('api/files/download/' + file.id, {}, config).then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', file.file_name);
                    document.body.appendChild(link);
                    link.click();
            }).catch(error => console.log(error));
        }
    }
}
</script>