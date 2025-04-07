// DOM Elements
const header = document.getElementById('header');
const themeToggles = document.querySelectorAll('.theme-toggle');
const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const mobileNav = document.querySelector('.mobile-nav');
const filterBtns = document.querySelectorAll('.filter-btn');
const projectCards = document.querySelectorAll('.project-card');
const testimonialSlides = document.querySelectorAll('.testimonial-slide');
const prevBtn = document.querySelector('.prev-btn');
const nextBtn = document.querySelector('.next-btn');
const dots = document.querySelectorAll('.dot');
const contactForm = document.getElementById('contactForm');
const backToTopBtn = document.querySelector('.back-to-top');

// Variables
let currentSlide = 0;

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
  // Check for saved theme preference
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    document.body.classList.add('dark-mode');
  }
  
  // Initialize the website
  initializeWebsite();
});

// Function to initialize the website
function initializeWebsite() {
  // Theme Toggle
  themeToggles.forEach(toggle => {
    toggle.addEventListener('click', toggleTheme);
  });
  
  // Mobile Menu
  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', toggleMobileMenu);
  }
  
  // Project Filters
  if (filterBtns.length > 0) {
    filterBtns.forEach(btn => {
      btn.addEventListener('click', filterProjects);
    });
  }
  
  // Testimonial Carousel
  if (testimonialSlides.length > 0) {
    // Set up the carousel interval
    setInterval(nextSlide, 5000);
    
    // Navigation buttons
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    
    // Dots navigation
    if (dots.length > 0) {
      dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          goToSlide(index);
        });
      });
    }
  }
  
  // Contact Form Validation
  if (contactForm) {
    contactForm.addEventListener('submit', validateForm);
  }
  
  // Back to Top Button
  window.addEventListener('scroll', toggleBackToTopBtn);
  if (backToTopBtn) {
    backToTopBtn.addEventListener('click', scrollToTop);
  }
  
  // Smooth Scrolling for Navigation Links
  const navLinks = document.querySelectorAll('a[href^="#"]');
  navLinks.forEach(link => {
    link.addEventListener('click', smoothScroll);
  });
  
  // Header Scroll Effect
  window.addEventListener('scroll', headerScrollEffect);
}

// Theme Toggle Function
function toggleTheme() {
  document.body.classList.toggle('dark-mode');
  
  // Save theme preference to localStorage
  if (document.body.classList.contains('dark-mode')) {
    localStorage.setItem('theme', 'dark');
  } else {
    localStorage.setItem('theme', 'light');
  }
}

// Mobile Menu Toggle Function
function toggleMobileMenu() {
  mobileNav.classList.toggle('active');
  
  // Toggle hamburger animation
  const spans = mobileMenuBtn.querySelectorAll('span');
  spans[0].classList.toggle('rotate-down');
  spans[1].classList.toggle('fade-out');
  spans[2].classList.toggle('rotate-up');
}

// Project Filter Function
function filterProjects() {
  // Remove active class from all buttons
  filterBtns.forEach(btn => {
    btn.classList.remove('active');
  });
  
  // Add active class to clicked button
  this.classList.add('active');
  
  // Get filter value
  const filter = this.getAttribute('data-filter');
  
  // Filter projects
  projectCards.forEach(card => {
    if (filter === 'all' || card.getAttribute('data-category') === filter) {
      card.style.display = 'block';
      setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'scale(1)';
      }, 100);
    } else {
      card.style.opacity = '0';
      card.style.transform = 'scale(0.8)';
      setTimeout(() => {
        card.style.display = 'none';
      }, 300);
    }
  });
}

// Testimonial Carousel Functions
function goToSlide(slideIndex) {
  // Hide all slides
  testimonialSlides.forEach(slide => {
    slide.classList.remove('active');
  });
  
  // Show the selected slide
  testimonialSlides[slideIndex].classList.add('active');
  
  // Update dots
  dots.forEach(dot => {
    dot.classList.remove('active');
  });
  dots[slideIndex].classList.add('active');
  
  // Update current slide index
  currentSlide = slideIndex;
}

function nextSlide() {
  let nextIndex = currentSlide + 1;
  if (nextIndex >= testimonialSlides.length) {
    nextIndex = 0;
  }
  goToSlide(nextIndex);
}

function prevSlide() {
  let prevIndex = currentSlide - 1;
  if (prevIndex < 0) {
    prevIndex = testimonialSlides.length - 1;
  }
  goToSlide(prevIndex);
}

// Form Validation Function
function validateForm(e) {
  e.preventDefault();
  
  // Get form inputs
  const nameInput = document.getElementById('name');
  const emailInput = document.getElementById('email');
  const subjectInput = document.getElementById('subject');
  const messageInput = document.getElementById('message');
  
  // Validate inputs
  let isValid = true;
  
  // Name validation
  if (nameInput.value.trim() === '') {
    showError(nameInput, 'Name is required');
    isValid = false;
  } else {
    removeError(nameInput);
  }
  
  // Email validation
  if (emailInput.value.trim() === '') {
    showError(emailInput, 'Email is required');
    isValid = false;
  } else if (!isValidEmail(emailInput.value)) {
    showError(emailInput, 'Please enter a valid email');
    isValid = false;
  } else {
    removeError(emailInput);
  }
  
  // Subject validation
  if (subjectInput.value.trim() === '') {
    showError(subjectInput, 'Subject is required');
    isValid = false;
  } else {
    removeError(subjectInput);
  }
  
  // Message validation
  if (messageInput.value.trim() === '') {
    showError(messageInput, 'Message is required');
    isValid = false;
  } else {
    removeError(messageInput);
  }
  
  // Submit form if valid
  if (isValid) {
    // In a real implementation, you would send the form data to a server here
    // For demonstration purposes, we'll just show a success message
    showSuccessMessage();
    contactForm.reset();
  }
}

// Helper function for email validation
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Error display functions
function showError(input, message) {
  const formGroup = input.parentElement;
  const errorElement = formGroup.querySelector('.error-message') || document.createElement('div');
  
  if (!formGroup.querySelector('.error-message')) {
    errorElement.className = 'error-message';
    formGroup.appendChild(errorElement);
  }
  
  input.classList.add('is-invalid');
  errorElement.textContent = message;
}

function removeError(input) {
  const formGroup = input.parentElement;
  const errorElement = formGroup.querySelector('.error-message');
  
  if (errorElement) {
    formGroup.removeChild(errorElement);
  }
  
  input.classList.remove('is-invalid');
}

function showSuccessMessage() {
  const successMessage = document.createElement('div');
  successMessage.className = 'success-message';
  successMessage.textContent = 'Your message has been sent successfully!';
  
  contactForm.appendChild(successMessage);
  
  // Remove success message after 5 seconds
  setTimeout(() => {
    contactForm.removeChild(successMessage);
  }, 5000);
}

// Back to Top Button Functions
function toggleBackToTopBtn() {
  if (window.scrollY > 300) {
    backToTopBtn.classList.add('active');
  } else {
    backToTopBtn.classList.remove('active');
  }
}

function scrollToTop(e) {
  e.preventDefault();
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

// Smooth Scrolling Function
function smoothScroll(e) {
  e.preventDefault();
  
  const targetId = this.getAttribute('href');
  if (targetId === '#') return;
  
  const targetElement = document.querySelector(targetId);
  if (targetElement) {
    // Close mobile menu if open
    if (mobileNav.classList.contains('active')) {
      toggleMobileMenu();
    }
    
    // Scroll to target
    window.scrollTo({
      top: targetElement.offsetTop - 80, // Adjust for header height
      behavior: 'smooth'
    });
  }
}

// Header Scroll Effect
function headerScrollEffect() {
  if (window.scrollY > 50) {
    header.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.1)';
    header.style.background = 'var(--background-light)';
    header.style.height = '70px';
  } else {
    header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    header.style.background = 'var(--background-light)';
    header.style.height = '80px';
  }
}

// Additional CSS for mobile menu animation
const style = document.createElement('style');
style.textContent = `
  .mobile-menu-btn span {
    transition: all 0.3s ease;
  }
  
  .mobile-menu-btn .rotate-down {
    transform: rotate(45deg) translate(5px, 5px);
  }
  
  .mobile-menu-btn .fade-out {
    opacity: 0;
  }
  
  .mobile-menu-btn .rotate-up {
    transform: rotate(-45deg) translate(5px, -5px);
  }
  
  .is-invalid {
    border-color: #dc3545 !important;
  }
  
  .error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
  }
  
  .success-message {
    color: #10B981;
    font-size: 0.875rem;
    margin-top: 1rem;
    padding: 0.75rem;
    background-color: rgba(16, 185, 129, 0.1);
    border-radius: 0.375rem;
    text-align: center;
  }
`;
document.head.appendChild(style);

// Display form submission messages based on URL parameters
document.addEventListener('DOMContentLoaded', function() {
    // Check for message parameter in URL
    const urlParams = new URLSearchParams(window.location.search);
    const messageStatus = urlParams.get('message');
    
    // Get contact section to display messages
    const contactSection = document.getElementById('contact');
    
    if (messageStatus === 'success') {
        // Create success message element
        const successMessage = document.createElement('div');
        successMessage.className = 'alert-message success';
        successMessage.innerHTML = '<i class="fas fa-check-circle"></i> Thank you! Your message has been sent successfully.';
        
        // Insert after section header
        const sectionHeader = contactSection.querySelector('.section-header');
        sectionHeader.parentNode.insertBefore(successMessage, sectionHeader.nextSibling);
        
        // Remove message after 5 seconds
        setTimeout(() => {
            successMessage.remove();
            // Remove parameter from URL without reloading page
            window.history.replaceState({}, document.title, window.location.pathname);
        }, 5000);
    } else if (messageStatus === 'error') {
        // Create error message element
        const errorMessage = document.createElement('div');
        errorMessage.className = 'alert-message error';
        errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Sorry, there was a problem sending your message. Please try again later.';
        
        // Insert after section header
        const sectionHeader = contactSection.querySelector('.section-header');
        sectionHeader.parentNode.insertBefore(errorMessage, sectionHeader.nextSibling);
        
        // Remove message after 5 seconds
        setTimeout(() => {
            errorMessage.remove();
            // Remove parameter from URL without reloading page
            window.history.replaceState({}, document.title, window.location.pathname);
        }, 5000);
    }
}); 