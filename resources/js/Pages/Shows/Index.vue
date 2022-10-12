<template>
    <Layout>
        <div class="card">
            <div class="card-body">
                <Link href="/shows/create/">
                    <button class="btn btn-primary mt-2 mb-3">Create Show</button>
                </Link>
                <table class="table tabled-bordered dt-responsive no-wrap" id="showsTable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Buttons</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="show in shows">
                            <td>{{show.name}}</td>
                            <td>{{show.date}}</td>
                            <td>
                                <Link class="btn btn-primary" :href="'/shows/'+show.id+'/admin-dashboard'">Invite</Link>
                                <Link class="btn btn-secondary" :href="'/shows/'+show.id+'/view'">View</Link>
                                <Link class="btn btn-secondary" :href="'/shows/'+show.id+'/edit'">Edit</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from "../../Shared/Layout";
import {Link} from "@inertiajs/inertia-vue3";
export default {
    name: "Index",
    components: {Layout, Link},
    props: {
        'createShowUrl' : String,
        'shows': Array
    }
}
</script>

<style scoped>

</style>
