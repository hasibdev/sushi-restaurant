<template>
   <table class="table table-bordered">
      <thead>
         <th v-for="(row, i) in fields[0]" :key="i" style="vertical-align: middle">
            {{ row.label }}
         </th>
         <th class="text-right" width="5%" style="vertical-align: middle">
            <primary-button @click="add">
               <i class="fas fa-plus"></i>
            </primary-button>
         </th>
      </thead>

      <tbody>
         <tr v-for="(item, index) in items" :key="index">
            <td v-for="(field, fieldIndex) in fields[index]" :key="fieldIndex">
               <!-- Fields -->
               <slot :name="field.name" :index="index" :item="item" :field="field">
                  <component :is="field.component" v-model="item[field.name]" v-bind="{ ...field.props }" @input="handleComponentChange(
                     {
                        name: field.name,
                        index: index,
                        value: item[field.name]
                     })"></component>
               </slot>
            </td>

            <td>
               <primary-button variant="danger" @click="remove(index, item)">
                  <i class="fas fa-trash-alt"></i>
               </primary-button>
            </td>
         </tr>
      </tbody>

   </table>
</template>

<script>
import InputControl from '@/components/custom/InputControl'
import SelectField from '@/components/custom/SelectField'
import PrimaryButton from '@/components/custom/PrimaryButton'
export default {

   name: "repeatable",
   components: {
      InputControl, SelectField, PrimaryButton
   },
   props: {
      /**
       * Available field to show on table row
       */
      fields: {
         type: Array,
         required: true,
      },

      /**
       * Initial Model Value
       */
      value: {
         type: [Array, Object],
         default: () => [],
      },
   },

   data() {
      return {
         items: [],
      }
   },

   methods: {
      add() {
         this.$emit('onAdd')
      },
      remove(index, item) {
         this.$emit('onRemove', { index, item })
      },
      handleComponentChange(value) {
         this.$emit('fieldChange', value)
         // console.log('change', value)
      }
   },

   created() {
      // Set the initial value
      this.items = this.value
   },
   watch: {
      value(newValue) {
         this.items = newValue
      }
   },

}
</script>

<style lang="scss" scoped>
</style>