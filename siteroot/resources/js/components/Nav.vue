<template>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">Fixed navbar</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
                <router-link :to="{ name: 'Home' }" class="nav-link">Home</router-link> 
          </li>
          <li class="nav-item">
                <router-link :to="{ name: 'Login' }" class="nav-link">Login</router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" v-bind:class="{disable: !loggedIn}" href="#" @click.prevent="logout">Logout</a>
          </li>
        </ul>
      </div>
    </nav>
</template>

<script>
    export default {
        computed: {
            loggedIn: function () {
            const token = localStorage.getItem('user-token');
            if (token) {
                return true;
            }

            return false;
            } 
        },
        methods:{
            logout: function() {
                const token = localStorage.getItem('user-token')
                if (token) {
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' +token
                } else {
                    return;
                }

                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/logout',{},{withCredentials: true}).then(response => {
                        if (response.data.deleted) {
                            localStorage.removeItem('user-token');
                             this.$router.push('/login')
                        }
                    }).catch(error => console.log(error)); // credentials didn't match
                });
            }
        }
    }
</script>