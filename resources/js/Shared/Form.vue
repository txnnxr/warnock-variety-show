<template>
    <form :method="method" :action="action" @submit.prevent="submit" ref="form" :id="this.formId">
        <input type="hidden" name="_token" :value="$page.props.csrf_token">
        <input name="_method" type="hidden" value="PUT" v-if="method === 'PUT'">
        <slot />
    </form>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Form",
    props: {
        method: String,
        action: String,
    },
    data() {
        return {
            'formId': Math.random().toString(36).slice(2),
        }
    },
    methods: {
        submit() {
            const formData = new FormData(this.$refs['form']); // reference to form element
            const data = {}; // need to convert it before using not with XMLHttpRequest
            for (let [key, val] of formData.entries()) {
                Object.assign(data, { [key]: val })
            }
            switch (this.method) {
                case 'POST':
                    Inertia.post(this.action , data);
                    break;
                case 'PUT':
                    Inertia.put(this.action, data);
                    break;
                default:
                    break;
            }
            document.getElementById(this.formId).reset();
        },
    }
}
</script>

<style scoped>

</style>
