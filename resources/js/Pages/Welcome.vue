<template>
  <AppLayout>
    <!-- Balises meta spécifiques à cette page -->
    <Head>
      <title>USRA-CARE - Services à Domicile de Qualité - Bien-être au Quotidien</title>
      <meta name="description" content="Découvrez USRA-CARE, spécialiste des services à domicile depuis 2023. Ménage, garde d'enfants, auxiliaire de vie et jardinage par des intervenants qualifiés.">
      <meta name="keywords" content="services à domicile, aide ménagère, garde d'enfants, auxiliaire de vie, jardinage, USRA-CARE, Madagascar, services de qualité">
      <meta property="og:title" content="USRA-CARE - Services à Domicile de Qualité - Bien-être au Quotidien">
      <meta property="og:description" content="Découvrez nos services à domicile pour un quotidien simplifié et plus serein.">
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
import HeroContent from '@/components/Home/HeroContent.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import WhyAdvertise from '@/components/Home/WhyAdvertise.vue';
import Statistics from '@/components/Home/Statistics.vue';
import Testimonials from '@/components/Home/Testimonials.vue';
import Faq from '@/components/Home/Faq.vue';

const stats = ref([
  { icon: 'bi-calendar-check', value: '2+', label: "ans d'expérience" },
  { icon: 'bi-people-fill', value: '93%', label: 'clients satisfaits' },
  { icon: 'bi-percent', value: '50%', label: 'crédit d\'impôt' }
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
  window.location.href = route('quote');
};

const handleViewServices = () => {
  console.log('View services clicked');
  // Redirection vers la page services
  window.location.href = route('services');
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
/* Styles globaux adaptés à USRA-CARE */
.logo-font {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Couleurs de la palette USRA-CARE */
.bg-usra-dark {
  background-color: #0E4C59;
}

.bg-usra-medium {
  background-color: #1A6D7A;
}

.bg-usra-light {
  background-color: #33D0C3;
}

.bg-usra-accent {
  background-color: #2AB9AF;
}

.text-usra-dark {
  color: #0E4C59;
}

.text-usra-medium {
  color: #1A6D7A;
}

.text-usra-light {
  color: #33D0C3;
}

.text-usra-accent {
  color: #2AB9AF;
}

.border-usra-light {
  border-color: #33D0C3;
}

.border-usra-dark {
  border-color: #0E4C59;
}

.hover\:shadow-usra-light\/30:hover {
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