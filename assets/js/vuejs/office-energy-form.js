
var app = new Vue({
    el: '#vueapp',
    data() {
        return {
            menu_active: 'office-energy-list',
            authenCode: null,
            memberName: '',
            officeId: null,
            officeEnergyId: null,
            energyType: [],
            office_name: '',
            latitude: '',
            longitude: '',
            green_list: [],
            yearSelect: new Date().getFullYear() + 543,
            yearFromParam: '',
            energy_type: '',
            months: [{
                    month_th: 'มกราคม',
                    id: 1,
                    energy_value: ''
                },
                {
                    month_th: 'กุมภาพันธ์',
                    id: 2,
                    energy_value: ''
                },
                {
                    month_th: 'มีนาคม',
                    id: 3,
                    energy_value: ''
                },
                {
                    month_th: 'เมษายน',
                    id: 4,
                    energy_value: ''
                },
                {
                    month_th: 'พฤษภาคม',
                    id: 5,
                    energy_value: ''
                },
                {
                    month_th: 'มิถุนายน',
                    id: 6,
                    energy_value: ''
                },
                {
                    month_th: 'กรกฎาคม',
                    id: 7,
                    energy_value: ''
                },
                {
                    month_th: 'สิงหาคม',
                    id: 8,
                    energy_value: ''
                },
                {
                    month_th: 'กันยายน',
                    id: 9,
                    energy_value: ''
                },
                {
                    month_th: 'ตุลาคม',
                    id: 10,
                    energy_value: ''
                },
                {
                    month_th: 'พฤศจิกายน',
                    id: 11,
                    energy_value: ''
                },
                {
                    month_th: 'ธันวาคม',
                    id: 12,
                    energy_value: ''
                }
            ],
            messeageSigninError: '',
            getOfficeListData: [],
            office_sel_id: '',
            newOffice: false,
            updateOffice: false,
            localId: '',
            localList: [],
            provinceList: [],
            amphurList: [],
            provinceId: '',
            amphurId: '',
            provinceSelData: {},
            amphurSelData: {}
        }
    },
    computed: {
        years() {
            const year = new Date().getFullYear() + 543
            return Array.from({
                length: year - 2500
            }, (value, index) => year - index)
        },
        unitName() {
            let unitName = ''
            if (this.energyType.length > 0 && this.energy_type != '') {
                unitName = this.energyType.find((item) => item.energy_type_id == this.energy_type)
                    .energy_unit
            }
            return unitName

        }
    },
    created() {

        this.checkLoginData()
        this.getEnergyTypeData()
        this.getOfficeList()


        const params = new URLSearchParams(window.location.search)
        this.officeId = (params.has('officeId')) ? params.get('officeId') : null
        this.officeEnergyId = (params.has('officeEnergyId')) ? params.get('officeEnergyId') : null
        this.yearFromParam = (params.has('year')) ? parseInt(params.get('year')) : new Date()
            .getFullYear() + 543
        this.energy_type = (params.has('energyType') && params.get('energyType') != '') ? params.get(
            'energyType') : ''


        if (this.officeId !== null && this.officeEnergyId !== null) {
            this.updateOffice = true
            this.getEnergyData()
        } else {
            this.getGreenTypeData()
        }
    },
    mounted() {
        this.initMap()
    },
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
                        window.location.href = 'index.php'
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
        getGreenTypeData() {
            axios.post('api/action.php', {
                    action: 'get-green-type',
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.green_list = response

                    this.green_list.forEach((item, idx) => {
                        this.green_list[idx].selected = false
                    });
                })
                .catch((response) => {
                    //handle error
                })
        },
        getEnergyData() {
            this.authenCode = this.$cookies.get('authen_login')
            axios.post('api/action.php', {
                    action: 'get-energy-data',
                    authenCode: this.authenCode,
                    office_id: this.officeId,
                    office_energy_id: this.officeEnergyId,
                    year: this.yearFromParam - 543
                })
                .then(async (response) => {
                    //handle success
                    response = response.data

                    if (response.res_code == '00') {
                        this.office_name = await response.office_name_th
                        this.yearSelect = await parseInt(response.month[0].year) + 543
                        this.energy_type = await response.energy_type_id
                        this.latitude = await response.latitude
                        this.longitude = await response.longitude
                        this.green_list = await response.green_list

                        await this.getAddressLocalList('province')
                        this.provinceId = await response.province_id


                        if (this.provinceId != '') {
                            await this.getAddressLocalList('amphur', this.provinceId)
                            setTimeout(() => {
                                this.amphurId = response.amphur_id

                                if (this.amphurId != '') {
                                    this.getAddressLocalList('local', this.provinceId,
                                        this.amphurId)
                                    setTimeout(() => {
                                        this.localId = response.local_id
                                    }, 1000)


                                }

                            }, 1000)

                        }

                        await this.months.forEach((item, idx) => {
                            const monthTemp = response.month.find((item_res) => item_res
                                .month ==
                                item.id)
                            this.months[idx].energy_value = monthTemp.value
                        })


                    } else if (response.res_code == '02') {
                        this.messeageSigninError = 'URL ไม่ถูกต้อง ไม่พบข้อมูล'
                        const myToastEl = document.getElementById('submit_message_error')
                        const toast = new bootstrap.Toast(myToastEl)
                        toast.show()
                        setTimeout(async () => {
                            window.location.href = "office-energy-list.php"
                        }, 1000);
                    }

                })
                .catch((response) => {
                    //handle error
                })
        },
        insertEnergyData() {
            if (this.newOffice) {
                axios.post('api/action.php', {
                        action: 'insert-energy-data',
                        authenCode: this.authenCode,
                        office: this.office_name,
                        latitude: this.latitude,
                        longitude: this.longitude,
                        province_id: this.provinceId,
                        amphur_id: this.amphurId,
                        local_id: this.localId,
                        longitude: this.longitude,
                        green_list: this.green_list,
                        energy_type: this.energy_type,
                        year: this.yearSelect,
                        months: this.months

                    })
                    .then((response) => {
                        //handle success
                        response = response.data

                        if (response.res_code == '00') {
                            const myToastEl = document.getElementById('submit_message_success')
                            const toast = new bootstrap.Toast(myToastEl)
                            toast.show()
                            setTimeout(async () => {
                                window.location.href = "office-energy-list.php?energyType="+this.energy_type
                            }, 1000);
                        }

                    })
                    .catch((response) => {
                        //handle error
                    })
            } else {
                axios.post('api/action.php', {
                        action: 'insert-energy-data-already-office',
                        authenCode: this.authenCode,
                        office_id: this.office_sel_id,
                        latitude: this.latitude,
                        longitude: this.longitude,
                        province_id: this.provinceId,
                        amphur_id: this.amphurId,
                        local_id: this.localId,
                        longitude: this.longitude,
                        green_list: this.green_list,
                        energy_type: this.energy_type,
                        year: this.yearSelect,
                        months: this.months

                    })
                    .then((response) => {
                        //handle success
                        response = response.data

                        if (response.res_code == '00') {
                            const myToastEl = document.getElementById('submit_message_success')
                            const toast = new bootstrap.Toast(myToastEl)
                            toast.show()
                            setTimeout(async () => {
                                window.location.href = "office-energy-list.php?energyType="+this.energy_type
                            }, 1000);
                        }

                    })
                    .catch((response) => {
                        //handle error
                    })
            }
        },
        updateEnergyData() {
            axios.post('api/action.php', {
                    action: 'update-energy-data',
                    authenCode: this.authenCode,
                    office_id: this.officeId,
                    office_energy_id: this.officeEnergyId,
                    office: this.office_name,
                    latitude: this.latitude,
                    longitude: this.longitude,
                    province_id: this.provinceId,
                    amphur_id: this.amphurId,
                    local_id: this.localId,
                    green_list: this.green_list,
                    energy_type: this.energy_type,
                    year: this.yearSelect,
                    months: this.months
                })
                .then((response) => {
                    //handle success
                    response = response.data

                    if (response.res_code == '00') {
                        const myToastEl = document.getElementById('submit_message_success')
                        const toast = new bootstrap.Toast(myToastEl)
                        toast.show()
                    } else {

                        this.messeageSigninError = 'ระบบเกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'
                        const myToastEl = document.getElementById('submit_message_error')
                        const toast = new bootstrap.Toast(myToastEl)
                        toast.show()
                    }

                })
                .catch((response) => {
                    //handle error
                    this.messeageSigninError = 'ระบบเกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'
                    const myToastEl = document.getElementById('submit_message_error')
                    const toast = new bootstrap.Toast(myToastEl)
                    toast.show()
                })
        },
        getOfficeList() {
            axios.post('api/action.php', {
                    action: 'get-office-list',
                    authenCode: this.authenCode,
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.getOfficeListData = response
                })
                .catch((response) => {
                    //handle error
                })
        },
        addNewOffice() {
            this.newOffice = true
            this.getAddressLocalList('province');
        },
        getAddressLocalList(typeChange, province_id = '', amphur_id = '', callback = null) {
            if (typeChange == 'province') {
                axios.post('api/action.php', {
                        action: 'get-province-list'
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        this.provinceList = response
                        this.amphurList = []
                        this.amphurId = ''
                        this.localList = []
                        this.localId = ''

                        $('#amphur option[value=""]').prop('selected', true);
                        $('#local option[value=""]').prop('selected', true);
                    })
                    .catch((response) => {
                        //handle error
                    })
            } else if (typeChange == 'amphur') {
                axios.post('api/action.php', {
                        action: 'get-amphur-list',
                        province_id: province_id
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        this.amphurList = response
                        this.localList = []
                        this.localId = ''
                        this.amphurId = ''

                        $('#local option[value=""]').prop('selected', true);
                    })
                    .catch((response) => {
                        //handle error
                    })
            } else if (typeChange == 'local') {
                axios.post('api/action.php', {
                        action: 'get-local-list',
                        province_id: province_id,
                        amphur_id: amphur_id
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        this.localList = response
                        this.localId = ''
                    })
                    .catch((response) => {
                        //handle error
                    })
            }

            if (callback !== null) {
                callback()
            }

        },
        checkForm(e) {

            if (this.newOffice) {
                if (this.office_name != '' && this.energy_type != '') {
                    if (this.officeId !== null && this.officeEnergyId !== null) {
                        this.updateEnergyData()
                    } else {
                        this.insertEnergyData()
                    }
                } else {

                    if (this.office_name === '') {
                        this.messeageSigninError = 'กรุณาระบุสถานที่'
                    } else if (this.energy_type === '') {
                        this.messeageSigninError = 'กรุณาระบุประเภทพลังงาน'
                    }

                    const myToastEl = document.getElementById('submit_message_error')
                    const toast = new bootstrap.Toast(myToastEl)
                    toast.show()

                    e.preventDefault()

                }
            } else {
                if (this.energy_type != '') {
                    if (this.officeId !== null && this.officeEnergyId !== null) {
                        this.updateEnergyData()
                    } else {
                        this.insertEnergyData()
                    }
                } else {

                    if (this.energy_type === '') {
                        this.messeageSigninError = 'กรุณาระบุประเภทพลังงาน'
                    }

                    const myToastEl = document.getElementById('submit_message_error')
                    const toast = new bootstrap.Toast(myToastEl)
                    toast.show()

                    e.preventDefault()

                }
            }
        },
        clickCheckGreen(index, e) {
            this.green_list[index].selected = !this.green_list[index].selected
        },
        async selectOffice() {
            if (this.office_sel_id != '') {
                await this.getAddressLocalListFromSelectedOffice('province')
                setTimeout(async () => {
                    this.provinceId = await (this.getOfficeListData.find((item) => item
                                .office_id ==
                                this
                                .office_sel_id)
                            .province_id !== null) ? this.getOfficeListData.find((item) => item
                            .office_id ==
                            this
                            .office_sel_id)
                        .province_id : ''

                    if (this.provinceId != '') {
                        await this.getAddressLocalListFromSelectedOffice('amphur', this
                            .provinceId)
                        setTimeout(async () => {
                            this.amphurId = await (this.getOfficeListData.find((
                                            item) =>
                                        item
                                        .office_id == this
                                        .office_sel_id)
                                    .amphur_id !== null) ? this.getOfficeListData
                                .find((
                                        item) => item
                                    .office_id ==
                                    this
                                    .office_sel_id)
                                .amphur_id : ''


                            if (this.amphurId != '') {
                                await this.getAddressLocalListFromSelectedOffice(
                                    'local',
                                    this
                                    .provinceId, this
                                    .amphurId)
                                this.localId = await (this.getOfficeListData.find((
                                                item) =>
                                            item
                                            .office_id == this
                                            .office_sel_id)
                                        .local_id !== null) ? this.getOfficeListData
                                    .find((
                                            item) => item
                                        .office_id ==
                                        this
                                        .office_sel_id)
                                    .local_id : ''
                            }
                        }, 1000);

                    }

                }, 1000);

            }

        },

        getAddressLocalListFromSelectedOffice(typeChange, province_id = '', amphur_id = '', callback = null) {
            if (typeChange == 'province') {
                axios.post('api/action.php', {
                        action: 'get-province-list'
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        this.provinceList = response
                        this.amphurList = []
                        this.localList = []

                        $('#amphur option[value=""]').prop('selected', true);
                        $('#local option[value=""]').prop('selected', true);
                    })
                    .catch((response) => {
                        //handle error
                    })
            } else if (typeChange == 'amphur') {
                axios.post('api/action.php', {
                        action: 'get-amphur-list',
                        province_id: province_id
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        this.amphurList = response
                        this.localList = []

                        $('#local option[value=""]').prop('selected', true);
                    })
                    .catch((response) => {
                        //handle error
                    })
            } else if (typeChange == 'local') {
                axios.post('api/action.php', {
                        action: 'get-local-list',
                        province_id: province_id,
                        amphur_id: amphur_id
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        this.localList = response
                    })
                    .catch((response) => {
                        //handle error
                    })
            }

            if (callback !== null) {
                callback()
            }

        },
        initMap() {
            map = new L.Map('map')
            popup = new L.Popup()

            setTimeout(function() {
                map.invalidateSize()
            }, 200);

            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
                maxZoom: 18
            }).addTo(map)
            map.attributionControl.setPrefix('') // Don't show the 'Powered by Leaflet' text.

            const local = new L.LatLng(13.736717, 100.523186)
            // var local = new L.LatLng(13.8230634,100.9710903);
            map.setView(local, 10)

            // const markerLocation = new L.LatLng(13.736717, 100.523186);
            const RedIcon = L.Icon.Default.extend({
                options: {
                    iconUrl: 'assets/dist/leaflet/images/marker-icon.png'
                }
            });
            const redIcon = new RedIcon();
            L.marker(local, {
                icon: redIcon
            }).addTo(map);
            map.on('click', this.onMapClick);

            const searchControl = new L.esri.Controls.Geosearch().addTo(map);

            const results = new L.LayerGroup().addTo(map);

            searchControl.on('results', function(data) {
                results.clearLayers();
                for (var i = data.results.length - 1; i >= 0; i--) {
                    results.addLayer(L.marker(data.results[i].latlng));
                }
            });
        },
        onMapClick(e) {
            let res = e.latlng.toString().replace("LatLng(", "")
            res = res.replace(")", "")

            const text = res.split(",")

            this.latitude = text[0].trim()
            this.longitude = text[1].trim()

            popup
                .setLatLng(e.latlng)
                .setContent("<img src='assets/dist/leaflet/images/marker-icon.png'>&nbsp;" + e.latlng.toString())
                .openOn(map)
        }
    }
})