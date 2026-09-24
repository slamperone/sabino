// src/data/projects.ts

export interface Project {
  title: string;
  href: string;
  imgSrc: string;
  category?: string;
  tagline?: string;
}

export const projects: Project[] = [
  {
    title: "Global Partner Summit 2026",
    href: "/works/global-partner-summit-2026",
    imgSrc: "/img/home-work-1.jpg",
  },
  {
    title: "Platinum Circle 2024",
    href: "/works/platinum-circle-2024",
    imgSrc: "/img/home-work-2.jpg",
  },
  {
    title: "Service Recognition Event 2024",
    href: "/works/steeeezy",
    imgSrc: "/img/home-work-3.jpg",
  },
  {
    title: "Sunglass Hut Summit 2023",
    href: "/works/model-911",
    imgSrc: "/img/home-work-4.jpg",
  },
];
