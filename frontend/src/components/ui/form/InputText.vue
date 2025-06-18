<template>
  <div :class="{ 'mb-3': formGroup }">
    <label v-show="label" :for="name" class="mb-1">{{ label }}</label>
    <div :class="{ 'input-group': inputGroup }">
      <div v-if="inputGroup" class="input-group-text">
        <slot name="inputGroupText" />
      </div>
      <input
        v-bind="{ name, id, type, value: inputValue, placeholder }"
        class="form-control"
        :class="{ 'is-invalid': errorMessage }"
        @input="(event) => handleChange(event, false)"
        @blur="(event) => handleBlur(event, true)"
      />
      <div v-if="errorMessage" class="invalid-feedback">
        {{ errorMessage }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useField } from 'vee-validate'
import { toRef, useId } from 'vue'

const id = useId()

const props = withDefaults(
  defineProps<{
    name: string
    label?: string
    type?: string
    placeholder?: string
    value?: string
    formGroup?: boolean
    inputGroup?: boolean
  }>(),
  {
    type: 'text',
    formGroup: false,
    inputGroup: false,
  },
)
const name = toRef(props, 'name')

const {
  value: inputValue,
  errorMessage,
  handleBlur,
  handleChange,
  meta,
} = useField(name, undefined, {
  initialValue: props.value,
  validateOnValueUpdate: false,
})
</script>

<style scoped></style>
