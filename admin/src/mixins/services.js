export default {
   data() {
      return {
         savingState: false
      }
   },
   methods: {
      /**
       * Delete Resource
       */
      async deleteResource({ url, data, text = "You won't be able to revert this!" }) {
         try {
            const confirmation = await this.$swal({
               title: 'Are you sure?',
               text,
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, delete it!'
            })

            if (!confirmation.isConfirmed) {
               return Promise.reject('Delete cancle')
            }

            const res = await this.axios.delete(url, data)
            this.$toast.success('Deleted Successfully')
            return Promise.resolve(res)
         } catch (error) {
            console.error(error)
            this.$toast.error('Delete Fail')
            return Promise.reject(error)
         }
      },
      /**
       * Create Resource
       */
      async createResource({ url, data }) {
         this.savingState = true
         this.$v.$touch()
         if (this.$v.$invalid) {
            this.savingState = false
            this.$toast.warning('Please Fill all the field correctly')
            return Promise.reject({ message: 'Invalid Form Submissions' })
         }

         try {
            const res = await this.axios.post(url, data)
            this.$v.$reset()
            this.$toast.success('Created Successfully')

            return Promise.resolve(res)
         } catch (error) {
            console.error(error)
            this.$toast.error('Create Fail')
            return Promise.reject(error)
         } finally {
            this.savingState = false
         }
      },
      /**
       * Update Resource
       */
      async updateResource({ url, data }) {
         this.savingState = true
         try {
            const res = await this.axios.patch(url, data)

            this.$v.$reset()
            this.$toast.success('Updated Successfully')
            return Promise.resolve(res)
         } catch (error) {
            console.error(error)
            this.$toast.error('Update Fail')
            return Promise.reject(error)
         } finally {
            this.savingState = false
         }
      }
   },
}