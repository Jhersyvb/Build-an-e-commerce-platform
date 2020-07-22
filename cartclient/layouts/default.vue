<template>
  <div>
    <TheAlert />
    <Navigation />
    <Nuxt />
  </div>
</template>

<script>
import Navigation from '@/layouts/partials/Navigation'
import TheAler from '@/components/globals/TheAlert'

export default {
  components: { Navigation }
}
</script>
