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
    title: "Year in Review FY22 & FY23",
    href: "/works/year-in-review",
    imgSrc: "/img/home-work-13.jpg",
  },
  {
    title: "Launch Engagement Reports FY21 & FY23",
    href: "/works/launch-engagement-reports",
    imgSrc: "/img/home-work-14.jpg",
  },
  {
    title: "Service Recognition Event 2024",
    href: "/works/service-recognition-event-2024",
    imgSrc: "/img/home-work-3.jpg",
  },
  {
    title: "Sunglass Hut Summit 2023",
    href: "/works/sunglass-hut-summit-2023",
    imgSrc: "/img/home-work-4.jpg",
  },
  {
    title: "Invitational 2025",
    href: "/works/invitational-2025",
    imgSrc: "/img/home-work-5.jpg",
  },
  {
    title: "Thinking Forward Forum 2025",
    href: "/works/thinking-forward-forum-2025",
    imgSrc: "/img/home-work-6.jpg",
  },
  {
    title: "President's Award 2024",
    href: "/works/presidents-award-2024",
    imgSrc: "/img/home-work-7.jpg",
  },
  {
    title: "Winners Circle 2024",
    href: "/works/winners-circle-2024",
    imgSrc: "/img/home-work-8.jpg",
  },

];
