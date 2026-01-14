<template>
  <div class="absolute inset-0 z-0">
    <video autoplay muted loop playsinline class="w-full h-full object-cover">
      <source src="https://www.bluebillboard.co.uk/wp-content/uploads/2024/11/TL001.mp4" type="video/mp4">
      <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-jC7uo6dGTPtZEjpKIA9GblmgWJYPPA.png" 
           alt="Background publicitaire" class="w-full h-full object-cover">
    </video>
    <!-- Filtre noir avec opacité réglable -->
     
  
    <div class="absolute inset-0 bg-gradient-to-br from-ads-dark-blue/60 via-ads-dark-blue/60 to-ads-light-blue/60 backdrop-blur-sm bg-black/60"></div>
  </div>
</template>

<script>
export default {
  name: 'BackgroundVideo'
}
</script>