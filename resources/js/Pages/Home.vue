<template>
    <Layout>
        <div class="card">
            <div class="card-body p-3 py-5">
                <div class="text-center">
                    <Link :href=action class="btn btn-primary btn-lg p-4">Upcoming Show</Link>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from "../Shared/Layout";
import { Link } from '@inertiajs/inertia-vue3';

  export default {
  props: {
    action: String,
  },
  components: { Layout, Link },
}
</script>
