<template>
  <AppLayout>
    <!-- Balises meta spécifiques à cette page -->
    <Head>
      <title>Sandy Platform - Jus naturels & pipeline opérationnel</title>
      <meta name="description" content="Découvrez Sandy Platform, l’atelier camerounais qui pilote sourcing local, pressage à froid et livraison express de jus naturels.">
      <meta name="keywords" content="Sandy Platform,Sandy Juice,jus naturels,cold chain,pressage à froid,logistique,Yaoundé,Douala">
      <meta property="og:title" content="Sandy Platform - Jus naturels & pipeline opérationnel">
      <meta property="og:description" content="Approvisionnement local, atelier pressage et livraison 2h : explorez toute l’expérience Sandy.">
      <meta property="og:image" content="/images/logo.jpg">
      <meta property="og:url" :content="currentUrl">
      <meta property="twitter:card" content="summary_large_image">
      <link rel="canonical" :href="currentUrl">
    </Head>

    <div class="relative min-h-screen overflow-hidden">
      
        <HeroContent 
              :stats="stats"
              @start-project="handleStartProject"
              @view-services="handleViewServices"
            />
      <WhyAdvertise />
      <Statistics /> 
      <Testimonials />  
      <Faq />  
    </div>
  </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import HeroContent from '@/Components/Home/HeroContent.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import WhyAdvertise from '@/Components/Home/WhyAdvertise.vue';
import Statistics from '@/Components/Home/Statistics.vue';
import Testimonials from '@/Components/Home/Testimonials.vue';
import Faq from '@/Components/Home/Faq.vue';

const stats = ref([
  { icon: 'bi-droplet', value: '120+', label: 'recettes actives' },
  { icon: 'bi-truck', value: '2h', label: 'livraison moyenne' },
  { icon: 'bi-geo-alt', value: '43', label: 'fermes partenaires' }
]);

const currentUrl = ref('');

onMounted(() => {
  currentUrl.value = window.location.href;
});

// Méthodes
const handleLogoClick = () => {
  console.log('Logo clicked');
};

const handleContactClick = () => {
  console.log('Contact button clicked');
};

const handleMenuToggle = () => {
  console.log('Mobile menu toggled');
};

const handleStartProject = () => {
  window.location.href = route('contact');
};

const handleViewServices = () => {
  console.log('View services clicked');
  window.location.href = route('products');
};

const handleFormSubmit = (formData) => {
  console.log('Form submitted:', formData);
  // Ici vous pouvez envoyer les données à votre API
  // Exemple avec Inertia
  // Inertia.post('/contact', formData);
};

const handleFormSuccess = () => {
  console.log('Form submitted successfully');
  // Afficher un message de succès ou rediriger
};
</script>

<style>
/* Styles globaux inspirés par Sandy Platform */
.logo-font {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Couleurs de la palette Sandy Platform */
.bg-sandy-dark {
  background-color: #0E4C59;
}

.bg-sandy-medium {
  background-color: #1A6D7A;
}

.bg-sandy-light {
  background-color: #33D0C3;
}

.bg-sandy-accent {
  background-color: #2AB9AF;
}

.text-sandy-dark {
  color: #0E4C59;
}

.text-sandy-medium {
  color: #1A6D7A;
}

.text-sandy-light {
  color: #33D0C3;
}

.text-sandy-accent {
  color: #2AB9AF;
}

.border-sandy-light {
  border-color: #33D0C3;
}

.border-sandy-dark {
  border-color: #0E4C59;
}

.hover\:shadow-sandy-light\/30:hover {
  box-shadow: 0 10px 25px rgba(51, 208, 195, 0.3);
}

.text-shadow {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.text-shadow-lg {
  text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
}

.animate-pulse {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.8; }
  100% { opacity: 1; }
}

.animate-bounce {
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-8px); }
  60% { transform: translateY(-4px); }
}

/* Styles responsives */
@media (max-width: 1024px) {
  .grid-cols-\[1fr_400px\] {
    grid-template-columns: 1fr;
  }
  
  .gap-16 {
    gap: 2rem;
  }
}
</style>
