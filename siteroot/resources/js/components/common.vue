<script>

export default {
        fetchDataDateRange: function() { // fetch max & min dates from sales data.
            axios.get('api/sales/dateRange').then(response => {
                  this.datePickerDateRange = response.data
            }).catch(error => console.log(error));
        },
        Loggedin: function () {
          const token = localStorage.getItem('user-token');
          if (token) {
            return true;
          }

          return false;
        }
}
</script>
