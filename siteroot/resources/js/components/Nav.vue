<template>
<nav class="mb-1 navbar navbar-expand-lg navbar-dark fixed-top bg-primary">
      <a class="navbar-brand" href="#">P-T20</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item" v-bind:class="{ active: this.checkRoute('Home') }">
                <router-link :to="{ name: 'Home' }" class="nav-link">Dashboard</router-link> 
          </li>
          <li class="nav-item" v-bind:class="{ active: this.checkRoute('Login') }">
                <router-link :to="{ name: 'Login' }" class="nav-link">Login</router-link>
          </li>
          <li class="nav-item" v-bind:class="{ active: this.checkRoute('DataGrid') }">
                <router-link :to="{ name: 'DataGrid' }" class="nav-link">Data Grid</router-link>
          </li>
          <li class="nav-item" v-bind:class="{ active: this.checkRoute('EmployeeLadder') }">
                <router-link :to="{ name: 'EmployeeLadder' }" class="nav-link">Employee Ladder</router-link>
          </li>
          <li class="nav-item" v-bind:class="{ active: this.checkRoute('MyFolder') }">
                <router-link :to="{ name: 'MyFolder' }" class="nav-link">My Folder</router-link>
          </li>
        </ul>
      </div>
        <div class="navbar-collapse collapse order-2 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a v-if="!loading" class="nav-link" href="#" @click.prevent="logout">Logout</a>
                    <p v-if="loading">logging out...</p>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        data() {
            return {
                loading: false
            }
        },
        methods:{
            logout: function() {
                if (localStorage.getItem('user-token') == null) {
                    return;
                }
                this.loading = true
                axios.post('/api/logout',{},{withCredentials: true}).then(response => {
                    if (response.data.deleted) {
                        localStorage.removeItem('user-token');
                            this.loading = false
                            this.$router.push('/login')
                    }
                }).catch(error => {
                    this.loading = false
                    console.log(error)
                });
            },
            checkRoute: function(route_name) {
                return this.$route.name == route_name;
            }
        }
    }
</script>