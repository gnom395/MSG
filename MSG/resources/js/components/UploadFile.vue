<template>


  <div class="container">
    <div class="progress" style="height: 40px">
      <div class="progress-bar" role="progressbar" :style="{ width: fileProgress + '%' }">
        {{ fileCurrent }} %
      </div>
    </div>

    <hr>

  <!--  <input type="file" name="image" multiple="" @change="fileInputChange">-->
    <hr>

    <div class="row">
      <div class="col-sm-6">
        <h3 class="list-center">Файлы в очереди ({{ filesOrder.length }})</h3>
          <ul class="list-group">
            <li class="list-group-item" v-for="file in filesOrder">
              {{ file.name }} : {{ file.type }}
            </li>
          </ul>
      </div>
      <div class="col-sm-6">
        <h3 class="list-center">Загруженные файлы ({{ filesFinish.length }})</h3>
          <ul class="list-group">
            <li class="list-group-item" v-for="file in filesFinish">
              {{ file.name }} : {{ file.type }}
            </li>
          </ul>
      </div>
    </div>
        </div>



</template>

<script>
  export default {
    data() {
        return {
          filesOrder: [],
          filesFinish: [],
          fileProgress: 0,
          fileCurrent: ''
        }
        },
        mounted() {
          /// из form
          this.$root.$on('fileInputChange', () => {
            this.fileInputChange()
          })

        },
        methods: {
          async fileInputChange(){
            let files = Array.from(event.target.files);

            this.filesOrder = files.slice()

            for(let item of files) {
              await this.uploadFiles(item)
            }
          },
          async uploadFiles(item) {

            let form = new FormData()
            form.append('file', item)

            await axios.post('/upload', form, {
                onUploadProgress: (itemUpload) => {
                  this.fileProgress = Math.round( (itemUpload.loaded / itemUpload.total) * 100 )
                  this.fileCurrent = item.name + ' ' + this.fileProgress
                }
            })
            .then(response => {
              console.log(response);
                if(response.status == 200) {
                  this.fileProgress = 0;
                  this.fileCurrent = '';
                  this.filesFinish.push(item);
                  this.filesOrder.splice(item, 1);
                } else {
                  alert('Ошибка');
                }

            })
            .catch(error => {
              alert('Ошибка. Файл слишком большой.');
              console.log(error);
            })
          }
        }

  }
</script>

<style scoped>

</style>
