<template>
  <nav>
    <div class="row">
<!--        @if(Auth::user())-->

            <div v-if="$page.props.user.name" class="col">
                <Form method="POST" :action="$page.props.links.logout">
                    <a :href="$page.props.links.logout"
                       onclick="event.preventDefault();
                       this.closest('form').submit();">
                        Logout
                    </a>
                </Form>
            </div>
            <div v-if="$page.props.user.name" class="col">
                <Link href="/shows">Shows</Link>
            </div>
<!--        @else-->
            <a v-if="!$page.props.user.name" class="col" :href="$page.props.links.login"> Login </a>
<!--        @endif-->
        <div class="col hideable-nav">
            <Link v-if="$page.props.latest_show_link" :href="$page.props.latest_show_link">Upcoming Show</Link>
        </div>
    </div>
  </nav>
</template>
<script>
  import { Link } from '@inertiajs/inertia-vue3';
  import Form from "../Shared/Form";

  export default {
    components: { Link, Form },
  }
</script>

<style scoped>

</style>
