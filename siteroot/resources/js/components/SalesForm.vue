<template>
    <form action="#" @submit.prevent="submitSalesForm">
    <p v-if="errors.length" class="alert alert-danger" role="alert">
        <b>Please correct the following error(s):</b>
        <ul>
            <li v-for="error in errors" :key="error">{{ error }}</li>
        </ul>
    </p>
    <div class="alert alert-success" role="alert" v-if="success">
        {{message}}
    </div>
    <div class="form-group row align-items-center" v-if="ready">
        <div class="col">
            <label for="product">For Customer</label>
            <multiselect v-model="customer" deselect-label="Can't remove this value" track-by="id" label="full_name" placeholder="Select one customer" :options="customerOptions" :searchable="true" :allow-empty="false"></multiselect>
            <small id="customerHelp" class="form-text text-muted">If you cannot find the customer from the list, please add the customer.</small>
        </div>
        <div class="col">      
            <router-link :to="{ name: 'CustomerForm' }" class="btn btn-success">Add new customer</router-link>
        </div>
    </div>
    <div class="spinner-border mt-5" role="status" v-if="!ready">
        <span class="sr-only">Loading...</span>
    </div>
    <div class="form-group" v-if="ready">
        <label for="product">Product</label>
        <multiselect v-model="product" deselect-label="Can't remove this value" track-by="productId" label="name" placeholder="Select one product" :options="productOptions" :searchable="true" :allow-empty="false"></multiselect>
    </div>
    <div class="form-group" v-if="ready">
        <label for="product">Sales Staff</label>
        <multiselect v-model="employee" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Select one staff" :options="employeeOptions" :searchable="true" :allow-empty="false"></multiselect>
    </div>
    <div class="form-group" v-if="ready">
        <label for="date">Sale Date</label>
        <date-picker v-model="saleDate" value-type="format" format="YYYY-MM-DD"></date-picker>
    </div>
    <div class="form-group" v-if="ready">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </form>
</template>
<script>
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import Multiselect from 'vue-multiselect';
    export default {
        components: { DatePicker, Multiselect },
        data() {
            return {
                saleDate: '',
                product: 0,
                productOptions: [],
                customer: 0,
                customerOptions: [],
                employee: 0,
                employeeOptions: [],
                ready: false,
                formData : {
                    date: '',
                    product: 0,
                    customer: 0,
                    employee:0
                },
                errors: [],
                success: false,
                message: ''
            }
        },
        mounted() {
            this.fillOptions()
        },
        methods: {
            fillOptions: function() {
                this.ready = false;
                axios.get('api/sales/formfilters').then(response => {
                    this.productOptions = response.data.products
                    this.customerOptions = response.data.customers
                    this.employeeOptions = response.data.employees
                    this.ready = true;
                }).catch(error => console.log(error));
            },
            submitSalesForm: function() {
                if (this.checkForm()) {
                    this.formData.date = this.saleDate
                    this.formData.product = this.product.productId
                    this.formData.customer = this.customer.id
                    this.formData.employee = this.employee.id
                    this.ready = false;
                    axios.post('/api/sales/add', this.formData).then(response => { 
                        this.message = response.data.success;
                        if (this.message) {
                             this.success = true;
                             this.formData = {}
                             this.saleDate = '';
                             this.product = 0;
                             this.customer = 0;
                             this.employee = 0;
                        }
                        this.ready = true
                    }).catch(error => {
                        this.ready = true
                        this.errors.push(error)
                        console.log(error)
                    }); 
                }
            },
            checkForm: function() {
                this.errors = [];
                this.success = false;
                let check  = true;
                if (!this.saleDate) {
                    this.errors.push('Date required.');
                    check = false;
                }

                if (this.product == 0) {
                    this.errors.push('Product required.');
                    check = false;
                }
                if (this.customer == 0) {
                    this.errors.push('Customer required.');
                    check = false;
                }

                if (this.employee == 0) {
                    this.errors.push('Sales staff required.');
                    check = false;
                }

                return check;
            },
            disabledBeforeToday: function(date) {
                //sales cannot be made in the future.
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                return date < today
            }
        }
    }
</script>