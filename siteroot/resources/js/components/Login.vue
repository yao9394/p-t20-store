<template>
<div id="login">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="#" @submit.prevent="handleLogin">
                            <h3 class="text-center text-info">Login</h3>
                            <div class="form-group">
                                <label for="email" class="text-info">Email:</label><br>
                                <input type="text" v-model="formData.email" name="email" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="password" v-model="formData.password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</div>
</template>
<script>
    export default {
        data() {
        return {
                secrets: [],
                formData: {
                    email: '',
                    password: ''
                }
            }
        },
        created: function () {
            const token = localStorage.getItem('user-token');
            if (token) {
              this.$router.push('/');
            }
        },
        methods: {
            handleLogin() {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/login', this.formData).then(response => {
                        const token = response.data.access_token
                        localStorage.setItem('user-token', token)
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' +token
                        this.$router.push('/');
                    }).catch(error => console.log(error)); // credentials didn't match
                });
            }
        }
    }
</script>