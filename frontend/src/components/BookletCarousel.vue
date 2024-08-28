<template>
    <div :id="'bookletCarousel' + recordId" class="carousel slide">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img :src="bookletfront" class="card-img-top" :alt="recordTitle">
            </div>
            <div class="carousel-item">
                <img :src="bookletback" class="card-img-top" :alt="recordTitle">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" :data-bs-target="'#bookletCarousel' + recordId"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" style="background-color:unset; filter: invert(0) grayscale(0)"
                aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" :data-bs-target="'#bookletCarousel' + recordId"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" style="background-color: unset; filter: invert(0) grayscale(0)"
                aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</template>

<script>
export default {
    name: 'BookletCarsousel',
    props: {
        recordId: {
            type: Number,
            required: true,
        },
        recordTitle: {
            type: String,
            required: true,
        },
        bookletfront: {
            type: String,
            required: true,
        },
        bookletback: {
            type: String,
            required: true,
        },
    },
}
</script>