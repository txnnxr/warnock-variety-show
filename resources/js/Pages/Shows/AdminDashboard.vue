<template>
    <Layout>
        <h2>{{ show.name }}</h2>
        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-center">Public RSVP Status</div>
                        <div class="fs-3 text-center text-uppercase">{{ show.public_rsvp_open_text }}</div>
                        <Form :action="'/shows/'+this.show.id+'/toggle-public-rsvp'" method="POST">
                            <button class="btn btn-primary width col-12" type="submit">
                                <span v-if="show.public_rsvp_open">Close</span>
                                <span v-if="!show.public_rsvp_open">Open</span>
                            </button>
                        </Form>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <!--                    <div class="card-body">-->
                    <!--                        {{-->
                    <!--                            &#45;&#45;-->
                    <!--                        <div class="card-title text-center">Public RSVP Status</div>-->
                    <!--                        &#45;&#45;}}-->
                    <!--                        {{-->
                    <!--                            &#45;&#45;-->
                    <!--                        <div class="fs-3 text-center text-uppercase">{{ $show->public_rsvp_open_text }}</div>-->
                    <!--                        &#45;&#45;}}-->
                    <!--                        {{-->
                    <!--                            &#45;&#45;-->
                    <!--                        <form action="{{action([\App\Http\Controllers\ShowController::class, 'togglePublicRsvp'], ['show' => $show])}}" method="POST">/&#45;&#45;}}-->
                    <!--                            {{ &#45;&#45;@csrf&#45;&#45; }}-->
                    <!--                            {{-->
                    <!--                                &#45;&#45;-->
                    <!--                            <button class="btn btn-primary width col-12" type="submit">@if($show->public_rsvp_open) Close @else Open @endif</button>-->
                    <!--                            &#45;&#45;}}-->
                    <!--                            {{ &#45;&#45;                    </form>-->
                    <!--                        &#45;&#45;}}-->
                    <!--                    </div>-->
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <!--                    <div class="card-body">-->
                    <!--                        {{-->
                    <!--                            &#45;&#45;-->
                    <!--                        <div class="card-title text-center">Public RSVP Status</div>-->
                    <!--                        &#45;&#45;}}-->
                    <!--                        {{-->
                    <!--                            &#45;&#45;-->
                    <!--                        <div class="fs-3 text-center text-uppercase">{{ $show->public_rsvp_open_text }}</div>-->
                    <!--                        &#45;&#45;}}-->
                    <!--                        {{-->
                    <!--                            &#45;&#45;-->
                    <!--                        <form action="{{action([\App\Http\Controllers\ShowController::class, 'togglePublicRsvp'], ['show' => $show])}}" method="POST">&#45;&#45;}}-->
                    <!--                            {{ &#45;&#45;@csrf&#45;&#45; }}-->
                    <!--                            {{-->
                    <!--                                &#45;&#45;-->
                    <!--                            <button class="btn btn-primary width col-12" type="submit">@if($show->public_rsvp_open) Close @else Open @endif</button>-->
                    <!--                            &#45;&#45;}}-->
                    <!--                            {{ &#45;&#45;                    </form>-->
                    <!--                        &#45;&#45;}}-->
                    <!--                    </div>-->
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <!--                    <div class="card-body">-->
                    <!--                        {{-->
                    <!--                            &#45;&#45;-->
                    <!--                        <div class="card-title text-center">Public RSVP Status</div>-->
                    <!--                        &#45;&#45;}}-->
                    <!--                        {{-->
                    <!--                            &#45;&#45;-->
                    <!--                        <div class="fs-3 text-center text-uppercase">{{ $show->public_rsvp_open_text }}</div>-->
                    <!--                        &#45;&#45;}}-->
                    <!--                        {{-->
                    <!--                            &#45;&#45;-->
                    <!--                        <form action="{{action([\App\Http\Controllers\ShowController::class, 'togglePublicRsvp'], ['show' => $show])}}" method="POST">&#45;&#45;}}-->
                    <!--                            {{ &#45;&#45;@csrf&#45;&#45; }}-->
                    <!--                            {{-->
                    <!--                                &#45;&#45;-->
                    <!--                            <button class="btn btn-primary width col-12" type="submit">@if($show->public_rsvp_open) Close @else Open @endif</button>-->
                    <!--                            &#45;&#45;}}-->
                    <!--                            {{ &#45;&#45;                    </form>-->
                    <!--                        &#45;&#45;}}-->
                    <!--                    </div>-->
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body p-4">
                <h5 class="card-title text-center">Generate Invite</h5>
                <Form :action="'/shows/'+show.id+'/invite'" class="row" method="POST">
                    <div class="col-md-6 my-1">
                        <input class="form-control" name="first_name" placeholder="First Name (required)" type="text">
                    </div>
                    <div class="col-md-6 my-1">
                        <input class="form-control" name="last_name" placeholder="Last Name (optional)" type="text">
                    </div>
                    <div class="col-md-6 my-1">
                        <input class="form-control" name="phone" placeholder="Phone (optional)" type="text">
                    </div>
                    <div class="col-md-6 my-1">
                        <input class="form-control" name="email" placeholder="Email (optional)" type="text">
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="has_plus_one_option">Give invitation optional plus one?</label>
                        <input class="" name="has_plus_one_option" type="checkbox" value="1">
                    </div>
                    <div class="col-md-12 my-1">
                        <button class="form-control btn btn-primary" type="submit">Create Invite</button>
                    </div>
                </Form>
            </div>
        </div>
        <div class="card my-3">
            <div class="card-title mt-3 px-3">
                <!--                <div class="row">-->
                <!--                    {{-->
                <!--                        &#45;&#45;-->
                <!--                    <div class="col">Total Invitations: {{ count($show->invites) }}</div>-->
                <!--                    &#45;&#45;}}-->
                <!--                    {{-->
                <!--                        &#45;&#45;-->
                <!--                    <div class="col">Attending: {{ count($show->attending_invites) }}</div>-->
                <!--                    &#45;&#45;}}-->
                <!--                    {{-->
                <!--                        &#45;&#45;-->
                <!--                    <div class="col">Maybe: {{ count($show->maybe_invites) }}</div>-->
                <!--                    &#45;&#45;}}-->
                <!--                    {{-->
                <!--                        &#45;&#45;-->
                <!--                    <div class="col">No: {{ count($show->no_invites) }}</div>-->
                <!--                    &#45;&#45;}}-->
                <!--                    {{-->
                <!--                        &#45;&#45;-->
                <!--                    <div class="col">Pending: {{ count($show->pending_invites) }}</div>-->
                <!--                    &#45;&#45;}}-->
                <!--                    {{-->
                <!--                        &#45;&#45;-->
                <!--                    <div class="col">Created: {{ count($show->created_invites) }}</div>-->
                <!--                    &#45;&#45;}}-->
                <!--                </div>-->
            </div>
            <div class="card-body">
                <table id="invitesTable" class="dt-responsive no-wrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Response</th>
                        <th>Talent</th>
                        <th>Buttons</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(invite, index) in invites">
                        <td>{{ index+1 }}</td>
                        <td>{{ invite.first_name }} {{ invite.middle_name }}
                            {{ invite.last_name }}
                            <span v-if="invite.has_plus_one_option">(+1)</span>

                        </td>
                        <!--                        <td>@if(invite.phone){{invite.phone}}@else{{invite.email}}@endif</td>-->
                        <td>{{ invite.response_status }}
                            <span v-if="invite.guest_request">- REQUESTED</span>
                        </td>
                        <td>
                            <span v-if="invite.talent">YES</span>
                            <span v-if="!invite.talent">NO</span>
                        </td>
                        <td>
                            <!--                            href="{{action([\App\Http\Controllers\InvitationController::class, 'edit'], ['invite' => $invite])}}" -->
                            <Link class="btn btn-secondary col" :href="'/invites/'+invite.id+'/edit'">Edit</Link>
                            <!--                            -->
                            <Form
                                v-if="invite.response_status === 'CREATED' && (invite.phone || invite.email)" :action="'/invite/'+invite.id+'/send'"
                                class="d-inline-block" method="POST">
                                <button type="submit" class="btn btn-warning col d-inline-block" href>Send</button>
                            </Form>
                            <a v-if="invite.response_status === 'CREATED'" class="btn btn-primary copy-link col" :data-link="'shows/'+this.show.id+'/invite/respond/'+invite.key">Link</a>
                            <!--                            TODO: fill in action and make async action="{{route('invites.guest-request.approve', ['invite' => $invite])}}"-->
                            <Form class="d-inline-block" method="POST" v-if="invite.guest_request">
                                <button type="submit" class="btn btn-success col d-inline-block" href>Approve</button>
                            </Form>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Layout>
</template>
<!--TODO: clear old data out of table on new entry

    Re add the guest rsvp functionaltiy-->

<script>
import Layout from "../../Shared/Layout";
import Form from "../../Shared/Form";
import {Inertia} from "@inertiajs/inertia";
import DataTable from 'datatables.net-vue3';
import {Link} from "@inertiajs/inertia-vue3";
import {onMounted} from "vue";

export default {
    name: "AdminDashboard",
    components: {Layout, Form, DataTable, Link},
    props: {
        show: Object,
        invites: Array,
    },
    setup() {
        onMounted(() => {
            // console.log('mount me');
            // $(document).ready(function () {
                $('#invitesTable').DataTable();
            // });
        })
    }
}

</script>

<style scoped>

</style>
