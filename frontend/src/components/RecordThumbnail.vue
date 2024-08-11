<template>
    <div :id="'recordCarousel' + collectionId" class="carousel carousel-dark slide">
        <div class="carousel-inner">
            <div v-if="records.length === 0" class="carousel-item active">
                <div class="cards-wrapper">
                    <p>No records found.</p>
                </div>
            </div>

            <div v-else class="carousel-item" v-for="(chunk, index) in chunkedRecords" :key="index"
                :class="{ active: index === 0 }">
                <div class="d-flex justify-content-center">
                    <div v-for="record in chunk" :key="record.id" class="card p-0 record-card me-2 ms-2">
                        <RouterLink
                            :to="{ name: 'ShowRecord', params: { recordId: record.id, collectionId: collectionId } }">
                            <img :src="getRecordImage(record)" class="card-img-top" :alt="record.title">
                            <div class="card-body record-card-body">
                                <h5 class="card-title">{{ record.title }}</h5>
                                <p class="card-text">{{ record.artist }}</p>
                            </div>
                        </RouterLink>
                        <div class="record-actions">
                            <button class="btn btn-danger btn-sm">Delete</button>
                            <button class="btn btn-primary btn-sm">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" :data-bs-target="'#recordCarousel' + collectionId" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" :data-bs-target="'#recordCarousel' + collectionId" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';

export default {
    name: 'RecordThumbnail',
    props: {
        collectionId: {
            type: Number,
            required: true,
        },
    },
    setup(props) {
        const records = ref([]);
        const recordsPerPage = ref(4);

        const updateRecordsPerPage = () => {
      const width = window.innerWidth;
      if (width < 576) {
        recordsPerPage.value = 1;
      } else if (width < 768) {
        recordsPerPage.value = 2;
      } else if (width < 992) {
        recordsPerPage.value = 3;
      } else {
        recordsPerPage.value = 4;
      }
    };

    onMounted(() => {
      updateRecordsPerPage();
      window.addEventListener('resize', updateRecordsPerPage);
      fetchRecords();
    });

    onBeforeUnmount(() => {
      window.removeEventListener('resize', updateRecordsPerPage);
    });

        const fetchRecords = async () => {
            try {
                const response = await axios.get(`/api/collection/${props.collectionId}/records`, {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('vue-token'),
                    },
                });
                records.value = response.data;
            } catch (error) {
                console.error('Failed to fetch records:', err);
            }
        };

        const chunkedRecords = computed(() => {
            const chunks = [];
            for (let i = 0; i < records.value.length; i += recordsPerPage.value) {
                chunks.push(records.value.slice(i, i + recordsPerPage.value));
            }
            return chunks;
        });

        const getRecordImage = (record) => {
            const baseUrl = window.location.origin;
            if (record.bookletfront) {
                return `${record.bookletfront}`;
            } else if (record.bookletback) {
                return `${record.bookletback}`;
            } else {
                return '../assets/vue.svg';
            }
        };

        return {
            records,
            chunkedRecords,
            getRecordImage,
        };
    },
}
</script>

<style scoped>
a {
    color: inherit;
    text-decoration: none;
}
.carousel-container {
  position: relative;
  width: 100%;
  overflow: hidden;
  margin: auto;
}

.carousel-inner {
  display: flex;
  transition: transform 0.5s ease-in-out;
}

.carousel-item {
  flex: 1 0 100%;
  max-width: 100%;
}

.card-img-top {
  height: auto;
  object-fit: cover;
}

.record-card {
    margin: 0 10px;
    height: 100%;
    /* Match card height to carousel item height */
    width: 250px;
}

.record-actions {
    display: none;
    position: absolute;
    top: 10px;
    right: 10px;
    gap: 5px;
}

/* Only show the buttons on hover when the parent has the 'hover-enabled' class */
.hover-enabled .record-card:hover .record-actions {
    display: flex;
}

/* Adjust button styles */
.record-actions button {
    opacity: 0.8;
}

.card-img-top {
    height: 200px;
    object-fit: cover;
    border-radius: 0% !important;
}

.carousel-control-prev,
.carousel-control-next {
    width: 5% !important;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: black;
    padding: 15px;
    border-radius: 50%;
}
</style>
