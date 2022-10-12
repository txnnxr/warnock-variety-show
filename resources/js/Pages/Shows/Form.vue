<template>
    <Layout>
        <h2>
            <span v-if="show">Edit {{show.name}}</span>
            <span v-if="!show">Create A New Show</span>
        </h2>
        <Form class="form-check"
              :method="formMethod"
              :action="formAction">
            <label class="form-label col-3" for="name">Name:</label>
            <input class="form-control col-9" type="text" name="name" id="name" v-model="form.name"
                   placeholder="Name">
            <label class="form-label col-3" for="description">Description:</label>
            <textarea type="text" name="description" class="form-control col-9" rows="6" v-model="form.description"></textarea>
            <label class="form-label col-3" for="date">Address:</label>
            <input class="form-control col-9" type="text" name="address" id="address" v-model="form.address">
            <label class="form-label col-3" for="date">Date:</label>
            <input class="form-control col-9" type="datetime-local" name="date" id="date" v-model="form.date">
            <label class="form-label col-3" for="maxAttendants">Max Attendants:</label>
            <input class="form-control col-9" type="number" name="max_attendants" id="maxAttendants" v-model="form.max_attendants">
            <label class="form-label col-3" for="maxTalents">Max Talents:</label>
            <input class="form-control col-9" type="number" name="max_talents" id="maxTalents" v-model="form.max_talents">
            <button class="form-control btn btn-primary mt-3" type="submit">
                <span v-if="show">Edit</span>
                <span v-if="!show"> Create</span>
            </button>
        </Form>
    </Layout>
</template>

<script>
import {reactive} from "vue";
import {Inertia} from "@inertiajs/inertia";
import Form from "../../Shared/Form";
import Layout from "../../Shared/Layout";
export default {
    name: "ShowForm",
    props: {
        'show': Object
    },
    components: {Form, Layout},
    data() {
        return {
            'formAction': (this.show) ? "/shows/" + this.show.id : "/shows",
            'formMethod': (this.show) ? "PUT" : "POST",
            'form' : reactive({
                'name': this.show ? this.show.name : null,
                'description': this.show ? this.show.description : null,
                'address': this.show ? this.show.address : null,
                'date': this.show ? this.show.date : null,
                'max_attendants': this.show ? this.show.max_attendants : null,
                'max_talents': this.show ? this.show.max_talents : null,
            }),
            // 'submit' : () => {
            //     Inertia.put(this.formAction, this.form);
            // },
        }
    }
}



</script>

<style scoped>

</style>
