<template>
    <form action="#" @submit.prevent="submitCustomerForm">
        <p v-if="errors.length" class="alert alert-danger" role="alert">
            <b>Please correct the following error(s):</b>
            <ul>
                <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
        </p>
        <div class="alert alert-success" role="alert" v-if="success">
            {{message}}
        </div>
        <div class="form-group">
            <label for="firstname">First Name</label>
            <input class="form-control" v-model="formData.firstName" type="text" required>
        </div>
        <div class="form-group">
            <label for="lastname">Last Name</label>
            <input class="form-control" v-model="formData.lastName" type="text" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" v-model="formData.email" type="email" required>
        </div>
         <div class="form-group">
            <label for="gender">Gender</label>
                <select class="form-control" v-model="formData.gender">
                    <option value="">Leave it empty</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
        </div>
        <div class="form-group">
            <label for="street">Street</label>
            <input class="form-control" v-model="formData.street" type="text" required>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input class="form-control" v-model="formData.city" type="text" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <router-link :to="{ name: 'SalesForm' }" class="btn btn-success">Create sale record</router-link>
    </form>
</template>
<script>
    export default {
        data() {
            return {
                formData: {
                    firstName: '',
                    lastName: '',
                    email: '',
                    gender: '',
                    street: '',
                    city: '',
                },
                errors:[],
                success: false,
                message: ''
            }
        },
        methods: {
            submitCustomerForm: function() {
                axios.post('/api/customers/add', this.formData).then(response => { 
                    this.message = response.data.success;
                    if (this.message) {
                            this.success = true;
                            this.formData = {}
                    } else {
                        this.errors.push(response.data.message)
                    }
                }).catch(error => {
                    this.errors.push(error)
                    console.log(error)
                }); 
            }
        }
    }
</script>