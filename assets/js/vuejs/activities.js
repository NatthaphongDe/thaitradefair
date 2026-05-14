
var app = new Vue({
    el: '#vueapp',
    data() {
        return {
            menu_active: 'activities',
            authenCode: null,
            memberName: '',
            activity_list: []
        }
    },
    created() {
        this.checkLoginData()
        this.getActivitiesList()
    },
    mounted() {},
    methods: {
        redirectPage(url) {
            window.location.href = url
        },
        logout() {
            this.$cookies.remove('authen_login')
            window.location.href = 'index.php'
        },
        checkLoginData() {
            if (this.$cookies.get('authen_login') != undefined) {
                this.authenCode = this.$cookies.get('authen_login')

                axios.post('api/action.php', {
                        action: 'check-auth-login',
                        authenCode: this.authenCode
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        if (response.res_code == "00") {
                            this.memberName = response.member_data.firstname + ' ' + response.member_data
                                .lastname
                        } else {
                            this.$cookies.remove('authen_login')
                            window.location.href = "index.php";
                        }
                    })
                    .catch((response) => {
                        //handle error
                        this.$cookies.remove('authen_login')
                        window.location.href = "index.php";

                    });
            }
        },
        getActivitiesList() {
            axios.post('api/action.php', {
                    action: 'get-activites-list'
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.activity_list = response
                })
                .catch((response) => {
                    //handle error
                })
        }
    }
})