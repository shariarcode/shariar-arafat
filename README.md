# Shariar Arafat - Professional Portfolio Website

A professional and responsive portfolio website for Shariar Arafat, showcasing his skills, projects, and services.

![Portfolio Preview](images/portfolio-preview.jpg)

## Features

- **Fully Responsive Design**: Optimized for all devices (mobile, tablet, desktop)
- **Dark/Light Mode**: Seamless theme switching with user preference saved in local storage
- **SEO Optimized**: Proper meta tags and semantic HTML structure
- **Smooth Animations**: Page transitions and scroll effects
- **Project Filtering**: Interactive filter buttons to sort portfolio projects
- **Testimonial Carousel**: Automatic and manual testimonial slider
- **Contact Form**: With client-side validation
- **Performance Optimized**: Fast loading times and clean code

## Sections

1. **Hero Section**: Introduction with professional photo
2. **About Me**: Education, skills, and interests
3. **Projects**: Filterable portfolio grid
4. **Services**: Services offered with icons
5. **Testimonials**: Client testimonials carousel
6. **Contact**: Form with validation and social links
7. **Downloadable Resume**: PDF format

## Technical Specifications

### Color Scheme
- Primary: #4F46E5 (Indigo Blue)
- Accent: #10B981 (Emerald Green)
- Background (Light): #F9FAFB (Light Gray)
- Text: #1F2937 (Charcoal Black)
- Secondary: #D1D5DB (Slate Gray)

### Typography
- Primary Font: Inter

### Dependencies
- Font Awesome 6.4.0 (Icons)
- Google Fonts (Inter)

## Getting Started

### Prerequisites
- Any modern web browser (Chrome, Firefox, Safari, Edge)

### Local Development
1. Clone this repository:
   ```
   git clone <repository-url>
   ```

2. Open `index.html` in your browser

### Customization

#### Changing Content
- Edit the HTML files to update text content
- Replace images in the `images` folder with your own
- Update project information in the Projects section

#### Styling Changes
- Modify CSS variables in `css/styles.css` to change colors and fonts
- Adjust responsive breakpoints in the media queries if needed

#### Adding New Projects
Add new project cards by copying the existing project card structure:

```html
<div class="project-card" data-category="your-category">
    <div class="project-img">
        <img src="images/your-project-image.jpg" alt="Project Title">
    </div>
    <div class="project-info">
        <h3>Project Title</h3>
        <p>Project description goes here...</p>
        <div class="project-tech">
            <span>Tech 1</span>
            <span>Tech 2</span>
            <span>Tech 3</span>
        </div>
        <div class="project-links">
            <a href="#" class="btn btn-small">View Project</a>
            <a href="#" class="btn btn-small btn-secondary">Source Code</a>
        </div>
    </div>
</div>
```

## Performance Optimization

- Images are compressed for faster loading
- CSS is organized by sections for better maintainability
- JavaScript uses event delegation where possible
- Minimal use of external libraries

## Browser Compatibility

Tested and working in:
- Google Chrome
- Mozilla Firefox
- Safari
- Microsoft Edge

## Future Enhancements

- Blog section with categories
- Admin panel for content management
- Backend integration for contact form
- More animation effects
- Projects detail pages

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Font Awesome for the icons
- Google Fonts for the typography
- Unsplash for stock images

---

© 2025 Shariar Arafat. All Rights Reserved. 