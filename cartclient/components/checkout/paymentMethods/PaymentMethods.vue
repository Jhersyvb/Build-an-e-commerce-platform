<template>
  <article class="message">
    <div class="message-body">
      <h1 class="title is-5">Payment method</h1>

      <template v-if="selecting">
        <PaymentMethodSelector
          :payment-methods="paymentMethods"
          :selected-payment-method="selectedPaymentMethod"
          @click="paymentMethodSelected"
        />
      </template>
      <template v-else-if="creating">
        <PaymentMethodCreator @cancel="creating = false" @added="created" />
      </template>
      <template v-else>
        <template v-if="selectedPaymentMethod">
          {{ selectedPaymentMethod.card_type }} ending {{ selectedPaymentMethod.last_four }}
        </template>
        <div class="field is-grouped mt-5">
          <p class="control" v-if="paymentMethods.length">
            <a href="" class="button is-info" @click.prevent="selecting = true">
              Change payment method
            </a>
          </p>
          <p class="control">
            <a href="" class="button is-info" @click.prevent="creating = true">
              Add a payment method
            </a>
          </p>
        </div>
      </template>
    </div>
  </article>
</template>

<script>
import PaymentMethodSelector from './PaymentMethodSelector'
import PaymentMethodCreator from './PaymentMethodCreator'

export default {
  components: {
    PaymentMethodSelector,
    PaymentMethodCreator
  },

  props: {
    paymentMethods: {
      required: true,
      type: Array
    }
  },

  data() {
    return {
      selecting: false,
      creating: false,
      localPaymentMethod: this.paymentMethods,
      selectedPaymentMethod: null
    }
  },

  computed: {
    defaultPaymentMethod() {
      return this.localPaymentMethod.find(a => a.default === true)
    }
  },

  watch: {
    selectedPaymentMethod(paymentMethod) {
      this.$emit('input', paymentMethod.id)
    }
  },

  created() {
    if (this.paymentMethods.length) {
      this.switchPaymentMethod(this.defaultPaymentMethod)
    }
  },

  methods: {
    paymentMethodSelected(paymentMethod) {
      this.switchPaymentMethod(paymentMethod)
      this.selecting = false
    },

    switchPaymentMethod(paymentMethod) {
      this.selectedPaymentMethod = paymentMethod
    },

    created(paymentMethod) {
      this.localPaymentMethod.push(paymentMethod)
      this.creating = false

      this.switchPaymentMethod(paymentMethod)
    }
  }
}
</script>
