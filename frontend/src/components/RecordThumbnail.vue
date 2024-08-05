<template>
    <div id="recordCarousel" class="carousel carousel-dark slide mb-4" >
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="cards-wrapper">
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 1</h5>
                            <p class="card-text">Record 1</p>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="carousel-item">
                <div class="cards-wrapper">
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                    <div class="card me-3" style="width: 14rem;">
                        <img src="../assets/vue.svg" class="card-img-top rounded mx-auto d-block" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Record 2</h5>
                            <p class="card-text">Record 2</p>
                        </div>
                    </div>
                </div>
                
            </div>
          </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#recordCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#recordCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    <div>

  
  </div>
        
      </div>
      <h2>{{ collectionId }}</h2>
      <RouterLink type="button" :to="{name: 'CreateRecord', params: { collectionId: collectionId } }" class="btn mx-auto col-1 mb-3 btn-primary">Record erstellen</RouterLink>
    </template>
    
    <script>
    import { ref, computed, onMounted } from 'vue';
    import axios from 'axios';

    export default {
      name: 'RecordThumbnail',
      props: {
      collectionId: {
        type: Number,
        required: true,
      },
    },
    }
  
  
  </script>
    
  
  <style scoped>


  .cards-wrapper{
    display: flex;
  }
  </style>