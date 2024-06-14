<template>
  <div class="wrapper">
    <Slider />
    <div class="content-wrapper">
      <section class="content-header">
        <Header />
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid" style="margin-left: 20%">
          <div class="row">
            <div class="col-md-6 ">
              <div class="card card-danger">
                <div class="card-header">
                  <h3 class="card-title">Create Task</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Title</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-save"></i></span>
                      </div>
                      <input v-model="title" type="text" class="form-control">
                    </div>
                    <small v-if="!title" class="text-danger">Title is required</small>
                  </div>

                  <div class="form-group">
                    <label>Description</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                      </div>
                      <input v-model="description" type="text" class="form-control">
                    </div>
                    <small v-if="!description" class="text-danger">Description is required</small>
                  </div>

                  <div class="form-group">
                    <label>Execution Date</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                      <input v-model="execution_date" type="date" class="form-control">
                    </div>
                    <small v-if="!execution_date" class="text-danger">Execution Date is required</small>
                  </div>
                </div>
                <button @click="saveTask" class="btn btn-block btn-success">Save</button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <Footer />
  </div>
</template>

<script>
import axios from 'axios';
import Slider from '@/components/Slider.vue';
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";

export default {
  name: 'TaskCreate',
  components: {
    Slider,
    Header,
    Footer
  },
  data() {
    return {
      title: '',
      description: '',
      execution_date: ''
    };
  },
  methods: {
    saveTask() {
      if (!this.title || !this.description || !this.execution_date) {
        alert('Please fill in all required fields.');
        return;
      }

      // Prepare data for POST request
      const postData = {
        title: this.title,
        description: this.description,
        execution_date: this.execution_date
      };

      // Send POST request to the server
      axios.post(`${process.env.VUE_APP_BASE_URL}/tasks/add`, postData)
          .then(response => {
            if (response.data.success) {
              alert('Task was successfully added');
              this.$router.push({ name: 'Home' });
            } else {
              alert("Something went wrong, please try again later")
            }
          })
          .catch(error => {
            console.error('Error adding task:', error);
          });
    }
  }
};
</script>
