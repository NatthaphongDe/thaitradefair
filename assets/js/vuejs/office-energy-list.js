
var app = new Vue({
    el: '#vueapp',
    data() {
        return {
            menu_active: 'office-energy-list',
            authenCode: null,
            memberName: '',
            energyType: [],
            officeEnergyList: [],
            energyTypeTabActive: 1,
            unitText: '',
            energyTypeParam: null,
            searchQuery: '',
            energy_type: ''
        }
    },
    created() {
        this.checkLoginData()
        this.getEnergyTypeData()
        this.energyTypeChange(1, 'ลิตร')

        const params = new URLSearchParams(window.location.search)
        if (params.has('energyType')) {
            this.energyTypeTabActive = params.get('energyType')
        }
        this.energy_type = (params.has('energyType') && params.get('energyType') != '') ? params.get(
            'energyType') : ''

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
                        authenCode: this.authenCode,
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        if (response.res_code != '00') {
                            this.$cookies.remove('authen_login')
                            window.location.href = 'index.php'
                        } else {
                            this.memberName = response.member_data.firstname + ' ' + response.member_data
                                .lastname
                        }
                    })
                    .catch((response) => {
                        //handle error
                        this.$cookies.remove('authen_login')
                        window.location.href = "index.php";

                    });
            } else {
                window.location.href = 'index.php'
            }
        },
        getEnergyTypeData() {
            axios.post('api/action.php', {
                    action: 'get-energy-type'
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.energyType = response
                })
                .catch((response) => {
                    //handle error
                })
        },
        getEnergyList() {
            this.authenCode = this.$cookies.get('authen_login')
            axios.post('api/action.php', {
                    action: 'get-energy-list',
                    authenCode: this.authenCode,
                    energy_type: this.energyTypeTabActive
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.officeEnergyList = response
                })
                .catch((response) => {
                    //handle error
                })
        },
        async energyTypeChange(typeId, unit) {
            this.unitText = unit
            this.energyTypeTabActive = await typeId
            await this.getEnergyList()
        },
        goToEditDataPage(officeId, officeEnergyId, year) {
            window.location.href = 'office-energy-form.php?officeId=' + officeId + '&officeEnergyId=' +
                officeEnergyId + '&year=' +
                year
        },
        deleteEnergyData(id) {
            const myModal = new bootstrap.Modal(document.getElementById('modal-delete-confirm'))
            $('#modal-delete-confirm').modal('show')
            $('#modal-delete-confirm').data('delete-id', id)

        },
        deleteEnergyConfirmData() {
            const id = $('#modal-delete-confirm').data('delete-id')


            $('#modal-delete-confirm').modal('hide')

            axios.post('api/action.php', {
                    action: 'delete-energy-data',
                    authenCode: this.authenCode,
                    officeEnergyId: id
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    if (response.res_code == "00") {

                        this.officeEnergyList = this.officeEnergyList.filter(item => item
                            .office_energy_id != id)

                        const myToastEl = document.getElementById('submit_message_success')
                        const toast = new bootstrap.Toast(myToastEl)
                        toast.show()
                    }
                })
                .catch((response) => {
                    //handle error
                })
        },
        filterItems(presets) {
            return presets.filter((preset) => {
                let regex = new RegExp('(' + this.searchQuery + ')', 'i');
                return preset.office_name_th.match(regex);
            })
        }

    }
})