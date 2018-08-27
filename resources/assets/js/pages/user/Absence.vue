<template>
    <div>
        <span class="block w-full text-grey-lighter text-sm font-medium mb-1">Upcoming</span>

        <table class="w-full">
            <tr v-for="(absence, index) in absences" :key="index">
                <td width="150">{{ absence.date | date('DD/MM/YYYY') }}</td>
                <td>{{ absence.reason }}</td>
                <td>
                    <ui-icon
                        size="18"
                        name="trash"
                        color="grey-lighter"
                        @click.native="destroy(absence)"
                        class="float-right mt-1 cursor-pointer hover:opacity-75 select-none transition" />
                </td>
            </tr>
        </table>

        <hr class="block w-full my-8 h-px bg-off-white" />

        <ui-field label="Date" width="calc(25% - 0.5rem)" class="mr-2">
            <date-time-picker
                class="w-full"
                field="date"
                name="date"
                dateFormat="Y-m-d"
                placeholder="Pick the Saturday"
                :enable-time="false"
                :enable-seconds="false"
                @change="handleDatePicker"
            />
        </ui-field>

        <ui-field label="Reason (optional)" width="calc(60% - 0.5rem)" class="mr-2">
            <ui-input v-model="form.reason" width="100%" placeholder="Going out to dinner" />
        </ui-field>

        <ui-field>
            <ui-button @click="create" primary>Add</ui-button>
        </ui-field>
    </div>
</template>

<script>
    import DateTimePicker from '../../../../../nova/resources/js/components/DateTimePicker.vue'

    export default {
        components: {
            DateTimePicker
        },

        data() {
            return {
                absences: [],
                form: new Form({
                    date: null,
                    reason: null,
                })
            }
        },

        methods: {
            fetch() {
                return ajax.get('/api/absence')
                    .then(r => this.absences = r.data)
            },

            create() {
                return ajax.post('/api/absence', this.form.get())
                    .then(this.fetch)
            },

            destroy(absence) {
                return ajax.delete(`/api/absence/${absence.id}`)
                    .then(this.fetch)
            },

            handleDatePicker(value) {
                this.form.date = value
            }
        },

        created() {
            this.fetch()
        }
    }
</script>
