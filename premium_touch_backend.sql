-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 11, 2026 at 11:46 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `premium_touch_backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `blog_category_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('published','draft') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `views` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `blog_category_id`, `title`, `slug`, `content`, `author`, `status`, `views`, `created_at`, `updated_at`) VALUES
(1, 2, 'আধুনিক হোম ইন্টেরিয়র ডিজাইন: আপনার ঘরকে দিন নতুন জীবন', 'adhunik-hom-interizr-dijain-apnar-ghrke-din-ntun-jeebn-1768561823', '<p>একটি ঘর শুধু চারটি দেয়াল আর একটি ছাদ নয়—এটি আমাদের স্বপ্ন, স্বাচ্ছন্দ্য ও ব্যক্তিত্বের প্রতিফলন। সুন্দর ও পরিকল্পিত হোম ইন্টেরিয়র ডিজাইন আপনার দৈনন্দিন জীবনকে করে তোলে আরও আরামদায়ক, কার্যকর এবং নান্দনিক। আজকের আধুনিক জীবনে ইন্টেরিয়র ডিজাইনের মূল লক্ষ্য হলো কম জায়গায় সর্বোচ্চ ব্যবহার নিশ্চিত করা। সঠিক রঙ নির্বাচন, আলো-বাতাসের সঠিক ব্যবহার এবং আসবাবের স্মার্ট প্লেসমেন্ট একটি সাধারণ ঘরকেও অসাধারণ করে তুলতে পারে। হালকা রঙের দেয়াল ছোট ঘরকে বড় দেখাতে সাহায্য করে, আর প্রাকৃতিক আলো ঘরের পরিবেশকে করে তোলে প্রাণবন্ত। বাংলাদেশের আবহাওয়া ও সংস্কৃতির সঙ্গে মিল রেখে ইন্টেরিয়র ডিজাইন করা অত্যন্ত গুরুত্বপূর্ণ। কাঠ, বাঁশ বা প্রাকৃতিক টেক্সচারের ব্যবহার ঘরে এনে দেয় উষ্ণতা ও স্বাচ্ছন্দ্য। একই সঙ্গে আধুনিক ফার্নিচার ও মিনিমাল ডিজাইন ব্যবহার করলে ঘর দেখায় পরিপাটি ও রুচিশীল। একটি ভালো হোম ইন্টেরিয়র শুধু সৌন্দর্যই বাড়ায় না, এটি মানসিক প্রশান্তিও দেয়। কাজ শেষে নিজের সাজানো ঘরে ফিরে আসার অনুভূতি সত্যিই অনন্য। তাই নতুন বাড়ি হোক বা পুরোনো ঘরের রিনোভেশন—পেশাদার পরিকল্পনা ও সৃজনশীল ডিজাইনই পারে আপনার ঘরকে পরিণত করতে একটি আদর্শ আবাসে। আপনার ঘর, আপনার গল্প—সেটিকে সুন্দরভাবে তুলে ধরাই হোম ইন্টেরিয়র ডিজাইনের আসল উদ্দেশ্য। 🌿</p><p><br></p><p><img src=\"http://localhost/premium_touch/premium-touch-backend/public/storage/blogs/IEqzaC1oD6nau32Uly6k0GW8fNUbeEvD94RjUyXG.jpg\" alt=\"\" style=\"font-size: 1.1rem;\"></p><p><br></p><p>একটি ভালো হোম ইন্টেরিয়র শুধু সৌন্দর্যই বাড়ায় না, এটি মানসিক প্রশান্তিও দেয়। কাজ শেষে নিজের সাজানো ঘরে ফিরে আসার অনুভূতি সত্যিই অনন্য। তাই নতুন বাড়ি হোক বা পুরোনো ঘরের রিনোভেশন—পেশাদার পরিকল্পনা ও সৃজনশীল ডিজাইনই পারে আপনার ঘরকে পরিণত করতে একটি আদর্শ আবাসে। আপনার ঘর, আপনার গল্প—সেটিকে সুন্দরভাবে তুলে ধরাই হোম ইন্টেরিয়র ডিজাইনের আসল উদ্দেশ্য। 🌿<span class=\"ql-cursor\">﻿</span></p>', 'admin', 'published', 184, '2026-01-16 05:10:23', '2026-04-27 11:18:55'),
(2, 1, 'আধুনিক হোম ইন্টেরিয়র ডিজাইন: আপনার ঘরকে দিন নতুন জীবন', 'adhunik-hom-interizr-dijain-apnar-ghrke-din-ntun-jeebn-1768675562', '<p>একটি ঘর শুধু চারটি দেয়াল আর একটি ছাদ নয়—এটি আমাদের স্বপ্ন, স্বাচ্ছন্দ্য ও ব্যক্তিত্বের প্রতিফলন। সুন্দর ও পরিকল্পিত হোম ইন্টেরিয়র ডিজাইন আপনার দৈনন্দিন জীবনকে করে তোলে আরও আরামদায়ক, কার্যকর এবং নান্দনিক। আজকের আধুনিক জীবনে ইন্টেরিয়র ডিজাইনের মূল লক্ষ্য হলো কম জায়গায় সর্বোচ্চ ব্যবহার নিশ্চিত করা। সঠিক রঙ নির্বাচন, আলো-বাতাসের সঠিক ব্যবহার এবং আসবাবের স্মার্ট প্লেসমেন্ট একটি সাধারণ ঘরকেও অসাধারণ করে তুলতে পারে। হালকা রঙের দেয়াল ছোট ঘরকে বড় দেখাতে সাহায্য করে, আর প্রাকৃতিক আলো ঘরের পরিবেশকে করে তোলে প্রাণবন্ত। বাংলাদেশের আবহাওয়া ও সংস্কৃতির সঙ্গে মিল রেখে ইন্টেরিয়র ডিজাইন করা অত্যন্ত গুরুত্বপূর্ণ। কাঠ, বাঁশ বা প্রাকৃতিক টেক্সচারের ব্যবহার ঘরে এনে দেয় উষ্ণতা ও স্বাচ্ছন্দ্য।</p><p><br></p><p><img src=\"blob:http://localhost:5173/dec76eeb-95bb-4852-b19d-1b6a3cdcb8ee\" alt=\"\"></p><p><br></p><p> একই সঙ্গে আধুনিক ফার্নিচার ও মিনিমাল ডিজাইন ব্যবহার করলে ঘর দেখায় পরিপাটি ও রুচিশীল। একটি ভালো হোম ইন্টেরিয়র শুধু সৌন্দর্যই বাড়ায় না, এটি মানসিক প্রশান্তিও দেয়। কাজ শেষে নিজের সাজানো ঘরে ফিরে আসার অনুভূতি সত্যিই অনন্য। তাই নতুন বাড়ি হোক বা পুরোনো ঘরের রিনোভেশন—পেশাদার পরিকল্পনা ও সৃজনশীল ডিজাইনই পারে আপনার ঘরকে পরিণত করতে একটি আদর্শ আবাসে। আপনার ঘর, আপনার গল্প—সেটিকে সুন্দরভাবে তুলে ধরাই হোম ইন্টেরিয়র ডিজাইনের আসল উদ্দেশ্য। 🌿</p><p><br></p><p><img src=\"blob:http://localhost:5173/6f1cb79c-8ab2-4200-92f1-80bf33915796\" alt=\"\" style=\"font-size: 1.1rem;\"></p><p><br></p><p>কাজ শেষে নিজের সাজানো ঘরে ফিরে আসার অনুভূতি সত্যিই অনন্য। তাই নতুন বাড়ি হোক বা পুরোনো ঘরের রিনোভেশন—পেশাদার পরিকল্পনা ও সৃজনশীল ডিজাইনই পারে আপনার ঘরকে পরিণত করতে একটি আদর্শ আবাসে।﻿</p>', 'Admin', 'published', 90, '2026-01-17 12:46:02', '2026-05-04 06:20:19'),
(4, 2, 'আপনার ঘরকে দিন নতুন জীবন', 'আপনার-ঘরকে-দিন-নতুন-জীবন', '<p><span style=\"color: rgb(51, 51, 51); background-color: rgb(253, 253, 253);\">একটি ঘর শুধু চারটি দেয়াল আর একটি ছাদ নয়—এটি আমাদের স্বপ্ন, স্বাচ্ছন্দ্য ও ব্যক্তিত্বের প্রতিফলন। সুন্দর ও পরিকল্পিত হোম ইন্টেরিয়র ডিজাইন আপনার দৈনন্দিন জীবনকে করে তোলে আরও আরামদায়ক, কার্যকর এবং নান্দনিক। আজকের আধুনিক জীবনে ইন্টেরিয়র ডিজাইনের মূল লক্ষ্য হলো কম জায়গায় সর্বোচ্চ ব্যবহার নিশ্চিত করা। সঠিক রঙ নির্বাচন, আলো-বাতাসের সঠিক ব্যবহার এবং আসবাবের স্মার্ট প্লেসমেন্ট একটি সাধারণ ঘরকেও অসাধারণ করে তুলতে পারে। হালকা রঙের দেয়াল ছোট ঘরকে বড় দেখাতে সাহায্য করে, আর প্রাকৃতিক আলো ঘরের পরিবেশকে করে তোলে প্রাণবন্ত। বাংলাদেশের আবহাওয়া ও সংস্কৃতির সঙ্গে মিল রেখে ইন্টেরিয়র ডিজাইন করা অত্যন্ত গুরুত্বপূর্ণ। কাঠ, বাঁশ বা প্রাকৃতিক টেক্সচারের ব্যবহার ঘরে এনে দেয় উষ্ণতা ও স্বাচ্ছন্দ্য।</span></p><p><br></p><p><span style=\"color: rgb(51, 51, 51); background-color: rgb(253, 253, 253);\">একটি ঘর শুধু চারটি দেয়াল আর একটি ছাদ নয়—এটি আমাদের স্বপ্ন, স্বাচ্ছন্দ্য ও ব্যক্তিত্বের প্রতিফলন। সুন্দর ও পরিকল্পিত হোম ইন্টেরিয়র ডিজাইন আপনার দৈনন্দিন জীবনকে করে তোলে আরও আরামদায়ক, কার্যকর এবং নান্দনিক। আজকের আধুনিক জীবনে ইন্টেরিয়র ডিজাইনের মূল লক্ষ্য হলো কম জায়গায় সর্বোচ্চ ব্যবহার নিশ্চিত করা। সঠিক রঙ নির্বাচন, আলো-বাতাসের সঠিক ব্যবহার এবং আসবাবের স্মার্ট প্লেসমেন্ট একটি সাধারণ ঘরকেও অসাধারণ করে তুলতে পারে। হালকা রঙের দেয়াল ছোট ঘরকে বড় দেখাতে সাহায্য করে, আর প্রাকৃতিক আলো ঘরের পরিবেশকে করে তোলে প্রাণবন্ত। বাংলাদেশের আবহাওয়া ও সংস্কৃতির সঙ্গে মিল রেখে ইন্টেরিয়র ডিজাইন করা অত্যন্ত গুরুত্বপূর্ণ। কাঠ, বাঁশ বা প্রাকৃতিক টেক্সচারের ব্যবহার ঘরে এনে দেয় উষ্ণতা ও স্বাচ্ছন্দ্য।</span></p><p><br></p><p><span style=\"color: rgb(51, 51, 51); background-color: rgb(253, 253, 253);\">একটি ঘর শুধু চারটি দেয়াল আর একটি ছাদ নয়—এটি আমাদের স্বপ্ন, স্বাচ্ছন্দ্য ও ব্যক্তিত্বের প্রতিফলন। সুন্দর ও পরিকল্পিত হোম ইন্টেরিয়র ডিজাইন আপনার দৈনন্দিন জীবনকে করে তোলে আরও আরামদায়ক, কার্যকর এবং নান্দনিক। আজকের আধুনিক জীবনে ইন্টেরিয়র ডিজাইনের মূল লক্ষ্য হলো কম জায়গায় সর্বোচ্চ ব্যবহার নিশ্চিত করা। সঠিক রঙ নির্বাচন, আলো-বাতাসের সঠিক ব্যবহার এবং আসবাবের স্মার্ট প্লেসমেন্ট একটি সাধারণ ঘরকেও অসাধারণ করে তুলতে পারে। হালকা রঙের দেয়াল ছোট ঘরকে বড় দেখাতে সাহায্য করে, আর প্রাকৃতিক আলো ঘরের পরিবেশকে করে তোলে প্রাণবন্ত। বাংলাদেশের আবহাওয়া ও সংস্কৃতির সঙ্গে মিল রেখে ইন্টেরিয়র ডিজাইন করা অত্যন্ত গুরুত্বপূর্ণ। কাঠ, বাঁশ বা প্রাকৃতিক টেক্সচারের ব্যবহার ঘরে এনে দেয় উষ্ণতা ও স্বাচ্ছন্দ্য।</span></p><p><br></p><p><img src=\"http://localhost/premium_touch/premium-touch-backend/public/storage/blogs/5d9Ryy2IOAZVY48pDLw4V4qUexTIVeLszSPZFf9j.jpg\"></p><p><br></p><p><span style=\"color: rgb(51, 51, 51); background-color: rgb(253, 253, 253);\">একটি ঘর শুধু চারটি দেয়াল আর একটি ছাদ নয়—এটি আমাদের স্বপ্ন, স্বাচ্ছন্দ্য ও ব্যক্তিত্বের প্রতিফলন। সুন্দর ও পরিকল্পিত হোম ইন্টেরিয়র ডিজাইন আপনার দৈনন্দিন জীবনকে করে তোলে আরও আরামদায়ক, কার্যকর এবং নান্দনিক। আজকের আধুনিক জীবনে ইন্টেরিয়র ডিজাইনের মূল লক্ষ্য হলো কম জায়গায় সর্বোচ্চ ব্যবহার নিশ্চিত করা। সঠিক রঙ নির্বাচন, আলো-বাতাসের সঠিক ব্যবহার এবং আসবাবের স্মার্ট প্লেসমেন্ট একটি সাধারণ ঘরকেও অসাধারণ করে তুলতে পারে। হালকা রঙের দেয়াল ছোট ঘরকে বড় দেখাতে সাহায্য করে, আর প্রাকৃতিক আলো ঘরের পরিবেশকে করে তোলে প্রাণবন্ত। বাংলাদেশের আবহাওয়া ও সংস্কৃতির সঙ্গে মিল রেখে ইন্টেরিয়র ডিজাইন করা অত্যন্ত গুরুত্বপূর্ণ। কাঠ, বাঁশ বা প্রাকৃতিক টেক্সচারের ব্যবহার ঘরে এনে দেয় উষ্ণতা ও স্বাচ্ছন্দ্য।</span></p>', 'Admin', 'published', 94, '2026-01-17 12:55:02', '2026-04-20 07:44:30'),
(5, 2, 'আধুনিক ও আরামদায়ক জীবনের সেরা সমাধান', 'আধুনিক-ও-আরামদায়ক-জীবনের-সেরা-সমাধান', '<p>ডুপ্লেক্স বাড়ি এখন আধুনিক পরিবারের জন্য একটি জনপ্রিয় আবাসন ব্যবস্থা। নিচতলা ও ওপরতলার সুন্দর সমন্বয়ে তৈরি ডুপ্লেক্স হোম শুধু জায়গার সঠিক ব্যবহারই নয়, বরং একটি বিলাসবহুল ও নান্দনিক জীবনযাত্রাও নিশ্চিত করে। তবে একটি ডুপ্লেক্স বাড়িকে সত্যিকার অর্থে আকর্ষণীয় করে তুলতে সবচেয়ে গুরুত্বপূর্ণ ভূমিকা রাখে এর <strong>ইন্টেরিয়র ডিজাইন</strong>।</p><p><br></p><p><br></p><p><br></p><p><br></p><h2>ডুপ্লেক্স হোম ইন্টেরিয়রের গুরুত্ব</h2><p>ডুপ্লেক্স বাড়িতে সাধারণত লিভিং এরিয়া, ডাইনিং ও কিচেন নিচতলায় এবং বেডরুমগুলো ওপরতলায় থাকে। তাই ইন্টেরিয়র ডিজাইনে ফাংশনালিটি ও সৌন্দর্যের ভারসাম্য বজায় রাখা অত্যন্ত জরুরি। সঠিক ইন্টেরিয়র ডিজাইন বাড়ির প্রতিটি কোণকে করে তোলে ব্যবহারযোগ্য, আরামদায়ক ও দৃষ্টিনন্দন।</p><p><br></p><p><br></p><p><br></p><p><br></p><h2>ডুপ্লেক্স হোম ইন্টেরিয়রের প্রধান ক্যাটাগরি</h2><h3>১. লিভিং রুম ইন্টেরিয়র</h3><p>ডুপ্লেক্স বাড়ির লিভিং রুম সাধারণত একটু বড় হয়। এখানে আধুনিক সোফা সেট, ওয়াল প্যানেল ডিজাইন, ফলস সিলিং ও লাইটিং ব্যবহার করে একটি উষ্ণ ও স্টাইলিশ পরিবেশ তৈরি করা যায়।</p><p><br></p><p><br></p><p><br></p><p><br></p><h3>২. সিঁড়ির ডিজাইন (Staircase Interior)</h3><p>ডুপ্লেক্স হোমের অন্যতম আকর্ষণ হলো এর সিঁড়ি। কাঠ, গ্লাস বা মেটাল রেলিং ব্যবহার করে সিঁড়িকে একটি ফোকাল পয়েন্টে পরিণত করা যায়। সিঁড়ির নিচের জায়গা স্টোরেজ বা শোকেস হিসেবেও ব্যবহার করা যেতে পারে।</p><p><br></p><p><br></p><p><br></p><p><br></p><h3>৩. বেডরুম ইন্টেরিয়র</h3><p>ওপরতলার বেডরুমগুলোতে আরামকে প্রাধান্য দেওয়া হয়। সফট কালার, মিনিমাল ফার্নিচার, ওয়ারড্রোব ডিজাইন ও সঠিক লাইটিং বেডরুমকে করে তোলে শান্ত ও আরামদায়ক।</p><p><br></p><p><br></p><p><br></p><p><br></p><h3>৪. কিচেন ও ডাইনিং এরিয়া</h3><p>ডুপ্লেক্স হোমে মডুলার কিচেন এখন সবচেয়ে জনপ্রিয়। স্মার্ট স্টোরেজ, সহজ কাজের সুবিধা ও আধুনিক অ্যাপ্লায়েন্স ব্যবহারে কিচেন হয় সুন্দর ও কার্যকর। ডাইনিং এরিয়ায় সিম্পল লাইটিং ও এলিগ্যান্ট ফার্নিচার মানানসই।</p><p><br></p><p><br></p><p><br></p><p><br></p><h3>৫. ফলস সিলিং ও লাইটিং</h3><p>ডুপ্লেক্স বাড়ির উচ্চতা ও স্পেস অনুযায়ী ফলস সিলিং ডিজাইন করা যায়। LED লাইট, কভ লাইট ও স্পট লাইট ব্যবহার করে ঘরের সৌন্দর্য বহুগুণ বাড়ানো সম্ভব।</p><p><br></p><p><br></p><p><br></p><p><br></p><h2>ডুপ্লেক্স হোম ইন্টেরিয়রের আধুনিক ট্রেন্ড</h2><ul><li>মিনিমাল ও মডার্ন ডিজাইন</li><li>ন্যাচারাল কালার প্যালেট</li><li>মাল্টি-ফাংশনাল ফার্নিচার</li><li>স্মার্ট স্টোরেজ সলিউশন</li><li>এনার্জি সেভিং লাইটিং</li></ul><h2>কেন প্রফেশনাল ইন্টেরিয়র ডিজাইনার প্রয়োজন?</h2><p>একজন অভিজ্ঞ ইন্টেরিয়র ডিজাইনার আপনার ডুপ্লেক্স বাড়ির স্পেস, বাজেট ও লাইফস্টাইল অনুযায়ী সেরা ডিজাইন সমাধান দিতে পারেন। এতে সময় ও খরচ দুটোই সাশ্রয় হয় এবং আপনি পান একটি পরিকল্পিত ও মানসম্মত ইন্টেরিয়র।</p><p><br></p><p><br></p><p><br></p><p><br></p><h2>উপসংহার</h2><p>ডুপ্লেক্স হোম ইন্টেরিয়র মানেই শুধু সৌন্দর্য নয়, বরং একটি আরামদায়ক ও স্মার্ট জীবনধারা। সঠিক পরিকল্পনা ও আধুনিক ডিজাইনের মাধ্যমে আপনার ডুপ্লেক্স বাড়িকে রূপ দিন স্বপ্নের ঘরে।</p>', 'Admin', 'published', 66, '2026-01-19 11:03:49', '2026-04-29 10:49:50');

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Home Interior', 'home-interior', '2026-01-17 12:42:56', '2026-01-17 12:42:56'),
(2, 'Duplex Home Interior', 'duplex-home-interior', '2026-01-19 07:33:05', '2026-01-19 07:33:05'),
(3, 'Office Interior Design', 'office-interior-design', '2026-01-19 11:28:22', '2026-01-19 11:28:22'),
(4, 'Triplex Home Interior', 'triplex-home-interior', '2026-03-05 00:36:31', '2026-03-05 00:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `blog_images`
--

CREATE TABLE `blog_images` (
  `id` bigint UNSIGNED NOT NULL,
  `blog_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_images`
--

INSERT INTO `blog_images` (`id`, `blog_id`, `image_path`, `created_at`, `updated_at`) VALUES
(2, 2, 'blogs/PZKamqF1HxCxjiyiGcgyNR7xsqeO3Ntg0PMdG1Ga.jpg', '2026-01-17 12:46:03', '2026-01-17 12:46:03'),
(3, 2, 'blogs/uKz5cQ3gveOYEXVFv1OtK5fEfoESzlUzwluVUSuM.jpg', '2026-01-17 12:46:03', '2026-01-17 12:46:03'),
(6, 4, 'blogs/5d9Ryy2IOAZVY48pDLw4V4qUexTIVeLszSPZFf9j.jpg', '2026-01-19 07:44:02', '2026-01-19 07:44:02'),
(7, 1, 'blogs/IEqzaC1oD6nau32Uly6k0GW8fNUbeEvD94RjUyXG.jpg', '2026-01-19 07:56:05', '2026-01-19 07:56:05'),
(8, 5, 'blogs/VWnYnMkpajUElbW8Fy5VZntLCew790PL8D6iF5kE.jpg', '2026-01-19 11:03:50', '2026-01-19 11:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `blog_reactions`
--

CREATE TABLE `blog_reactions` (
  `id` bigint UNSIGNED NOT NULL,
  `blog_id` bigint UNSIGNED NOT NULL,
  `type` enum('like','dislike') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_reactions`
--

INSERT INTO `blog_reactions` (`id`, `blog_id`, `type`, `ip_address`, `created_at`, `updated_at`) VALUES
(4, 1, 'like', '::1', '2026-01-17 11:51:00', '2026-01-19 10:06:28'),
(6, 4, 'like', '::1', '2026-01-19 09:53:14', '2026-01-19 09:53:17'),
(7, 2, 'dislike', '::1', '2026-01-19 09:54:26', '2026-01-19 09:54:26'),
(8, 5, 'like', '::1', '2026-01-19 11:33:29', '2026-01-19 11:33:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `position` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Projects', 'projects', 0, 1, 1, NULL, NULL),
(2, 'Residential', 'residential', 1, 1, 1, NULL, NULL),
(3, 'Commercial', 'commercial', 1, 3, 1, NULL, NULL),
(4, 'Offices', 'offices', 3, 1, 1, NULL, NULL),
(5, 'Showroom', 'showroom', 3, 3, 1, NULL, NULL),
(6, 'Restaurants', 'restaurants', 3, 4, 1, NULL, NULL),
(7, 'Services', 'services', 0, 3, 1, NULL, NULL),
(11, 'Electrical', 'electrical', 9, 1, 1, NULL, NULL),
(12, 'Plumbing', 'plumbing', 9, 3, 1, NULL, NULL),
(13, 'Interior Setuphh HagsHHsa ', 'interior-setup', 9, 4, 1, NULL, NULL),
(14, 'Blog', 'blog', 0, 4, 1, NULL, NULL),
(15, 'Gallery', 'gallery', 0, 5, 1, NULL, NULL),
(16, 'Photo Gallery', 'photo-gallery', 15, 1, 1, NULL, NULL),
(17, 'Video Gallery', 'video-gallery', 15, 3, 1, NULL, NULL),
(18, 'About Us', 'about-us', 0, 6, 1, NULL, NULL),
(19, 'Contact', 'contact', 0, 7, 1, NULL, NULL),
(20, 'Bedroom', 'bedroom', 2, 2, 1, NULL, '2026-03-05 00:44:27'),
(21, 'Dining', 'dining', 2, 3, 1, NULL, '2026-03-05 00:45:48'),
(22, 'Kitchen', 'kitchen', 2, 4, 1, NULL, NULL),
(23, 'Industrial', 'industrial', 1, 4, 1, NULL, NULL),
(24, 'Portfolio', 'portfolio', 0, 2, 1, '2025-12-26 14:53:45', NULL),
(28, 'Residence', 'residence', 24, 1, 1, '2025-12-26 14:57:22', NULL),
(29, 'Office', 'office', 24, 2, 1, '2025-12-26 14:57:22', NULL),
(30, 'Showroom', 'portfolio-showroom', 24, 3, 1, '2025-12-26 14:57:22', NULL),
(31, 'Overview', 'about-overview', 18, 1, 1, '2025-12-26 14:59:29', NULL),
(32, 'Our Team', 'about-our-team', 18, 2, 1, '2025-12-26 14:59:29', NULL),
(33, 'Career', 'about-career', 18, 3, 1, '2025-12-26 14:59:29', NULL),
(35, 'Handover Snapshot', 'handover-snapshot', 15, 3, 1, '2025-12-26 15:07:28', NULL),
(36, 'Home Interior Design', 'home-interior-design', 7, 1, 1, '2025-12-26 15:13:05', NULL),
(37, 'Office Interior Design', 'office-interior-design', 7, 2, 1, '2025-12-26 15:13:05', NULL),
(38, 'Restaurant Interior Design', 'restaurant-interior-design', 7, 3, 1, '2025-12-26 15:13:05', NULL),
(39, 'Showroom Interior Design', 'showroom-interior-design', 7, 4, 1, '2025-12-26 15:13:05', NULL),
(40, 'Hotel Interior Design', 'hotel-interior-design', 7, 5, 1, '2025-12-26 15:13:05', NULL),
(41, 'Exterior Design', 'exterior-design', 7, 6, 1, '2025-12-26 15:13:05', NULL),
(42, 'Custom Furniture Design', 'custom-furniture-design', 7, 7, 1, '2025-12-26 15:13:05', NULL),
(43, 'Hospital', 'hospital', 3, 4, 1, '2026-02-09 08:37:46', '2026-02-09 08:37:46'),
(45, 'Bed Room', 'bed-room', 28, 0, 1, '2026-04-19 00:14:12', '2026-04-19 00:14:12'),
(52, 'Receptions', 'receptions', 29, 0, 1, '2026-04-19 03:31:06', '2026-04-19 03:46:29'),
(53, 'MD Rooms', 'md-rooms', 29, 0, 1, '2026-04-19 03:31:25', '2026-04-19 03:46:57'),
(55, 'Kitchen', 'kitchen-1', 28, 0, 1, '2026-04-19 03:37:24', '2026-04-19 03:37:24'),
(58, 'Resturants', 'resturants', 24, 0, 1, '2026-04-19 03:48:07', '2026-04-19 03:48:07'),
(60, 'Bathroom', 'bathroom', 28, 0, 1, '2026-04-19 04:57:27', '2026-04-19 04:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `blog_id` bigint UNSIGNED NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `is_admin_reply` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `blog_id`, `user_name`, `user_email`, `comment`, `is_approved`, `created_at`, `updated_at`, `parent_id`, `is_admin_reply`) VALUES
(4, 1, 'Sajid Rana', 'sajidrana013@gmail.com', 'Great interior blog post', 1, '2026-01-17 11:51:53', '2026-01-17 11:53:03', NULL, 0),
(5, 1, 'admin', 'admin@gmail.com', 'Thanks for comment', 1, '2026-01-17 11:54:05', '2026-01-17 11:54:05', 4, 1),
(6, 1, 'Fahim', 'fahim@gmail.com', 'Great work', 1, '2026-01-19 10:09:42', '2026-01-19 10:13:32', NULL, 0),
(7, 1, 'Fahim', 'fahim@gmail.com', 'nice', 1, '2026-01-19 10:26:40', '2026-01-19 10:26:40', NULL, 0),
(8, 1, 'admin', 'admin@gmail.com', 'Thank you. Stay with us', 1, '2026-01-19 10:39:51', '2026-01-19 10:39:51', 6, 1),
(9, 1, 'admin', 'admin@gmail.com', 'Stay with us', 1, '2026-01-19 10:40:33', '2026-01-19 10:40:33', 4, 1),
(10, 1, 'Rakib', 'masud@gmail.com', 'Really nice', 1, '2026-01-19 10:41:33', '2026-01-19 10:41:33', 7, 0),
(11, 1, 'admin', 'admin@gmail.com', 'Thank You. Stay with us', 1, '2026-01-19 11:13:55', '2026-01-19 11:13:55', 7, 1),
(12, 1, 'Md. Sajidul Islam', 'sajidulcse013@gmail.com', 'Thanks', 1, '2026-02-09 06:58:46', '2026-02-09 06:58:46', 7, 1),
(13, 1, 'Md. Sajidul Islam', 'sajidulcse013@gmail.com', 'thanks', 1, '2026-02-09 07:00:12', '2026-02-09 07:00:12', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `footer_sections`
--

CREATE TABLE `footer_sections` (
  `id` int NOT NULL,
  `section_title` varchar(255) NOT NULL,
  `section_type` enum('text','links','social','newsletter') NOT NULL DEFAULT 'text',
  `content` text,
  `display_order` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `footer_sections`
--

INSERT INTO `footer_sections` (`id`, `section_title`, `section_type`, `content`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Quick Links', 'links', '[\r\n    {\"title\": \"Projects\", \"url\": \"http://localhost:5174/projects\"},\r\n    {\"title\": \"Portfolio\", \"url\": \"http://localhost:5174/portfolio\"},\r\n    {\"title\": \"Services\", \"url\": \"http://localhost:5174/services\"},\r\n    {\"title\": \"Blog\", \"url\": \"http://localhost:5174/blog\"},\r\n    {\"title\": \"Gallery\", \"url\": \"http://localhost:5174/gallery\"},\r\n    {\"title\": \"About Us\", \"url\": \"http://localhost:5174/about-us\"},\r\n    {\"title\": \"Contact\", \"url\": \"http://localhost:5174/contact\"}\r\n]', 1, 1, '2025-12-26 16:00:56', '2025-12-26 16:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(5, '2025_12_07_163622_create_navbar_items_table', 2),
(6, '2025_12_07_172318_create_categories_table', 2),
(7, '2025_12_22_153538_create_contact_infos_table', 3),
(8, '2026_01_16_095952_create_blogs_table', 4),
(9, '2026_01_16_095953_create_blog_images_table', 4),
(10, '2026_01_16_095954_create_blog_reactions_table', 4),
(11, '2026_01_16_095954_create_comments_table', 4),
(12, '2026_01_16_102423_add_parent_id_and_admin_fields_to_comments_table', 5),
(13, '2026_01_16_102433_add_profile_picture_to_users_table', 5),
(14, '2026_01_16_113719_add_facebook_page_url_to_site_settings_table', 6),
(15, '2026_01_17_182616_create_blog_categories_table', 7),
(16, '2026_01_17_182617_add_blog_category_id_to_blogs_table', 8),
(17, '2026_02_09_135721_create_projects_table', 9),
(18, '2026_02_09_135729_create_project_images_table', 9),
(19, '2026_02_09_140246_add_category_id_to_projects_table', 10),
(20, '2026_02_09_143013_add_sub_and_child_categories_to_projects_table', 11),
(21, '2026_02_09_153504_add_duration_and_floor_area_to_projects_table', 12),
(22, '2026_02_09_172123_add_project_header_bg_to_site_settings_table', 13),
(23, '2026_04_19_070811_create_portfolios_table', 14),
(24, '2026_04_19_070813_create_portfolio_images_table', 14),
(25, '2026_04_19_081625_add_alt_text_to_portfolio_images_table', 15),
(26, '2026_04_20_105649_drop_client_location_date_from_portfolios_table', 16),
(27, '2026_04_27_163331_add_faqs_to_portfolios_table', 17),
(28, '2026_04_27_180955_add_premium_fields_to_services_table', 18),
(29, '2026_04_27_182622_modify_services_table_add_category_id', 19),
(30, '2026_04_27_182647_create_service_images_table', 20),
(31, '2026_04_29_132548_modify_services_table_to_match_portfolios', 21),
(32, '2026_04_29_135156_add_is_thumbnail_to_service_images_table', 22),
(33, '2026_04_29_140555_drop_unnecessary_columns_from_services_table', 23);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `faqs` json DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint UNSIGNED DEFAULT NULL,
  `child_category_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`id`, `title`, `slug`, `description`, `faqs`, `category_id`, `sub_category_id`, `child_category_id`, `status`, `created_at`, `updated_at`) VALUES
(6, 'Kitchen Interior Design', 'kitchen-interior-design', 'Create a bedroom that feels like your own private sanctuary—where comfort meets style and every detail is designed for relaxation. Our bedroom portfolio showcases a wide range of thoughtfully designed spaces, from modern minimal to luxurious and cozy interiors, tailored to suit different lifestyles and preferences.\r\n\r\nWe focus on blending aesthetics with functionality—optimizing layout, lighting, textures, and color palettes to create a calm and inviting atmosphere. Whether it’s smart storage solutions, elegant furniture placement, or mood-enhancing lighting, each design is crafted to improve both visual appeal and everyday comfort.\r\n\r\nExplore our bedroom designs to discover inspiration that transforms ordinary spaces into personalized retreats—designed to reflect your taste while ensuring maximum comfort and usability.', NULL, 24, 28, 55, 'published', '2026-04-19 02:47:44', '2026-04-19 03:53:29'),
(7, 'Resturants', 'resturants', 'Designing a restaurant goes beyond aesthetics—it’s about creating an atmosphere that enhances the dining experience and reflects the brand’s identity. Our restaurant interior portfolio showcases thoughtfully designed spaces that balance style, comfort, and functionality to leave a lasting impression on every guest.\r\n\r\nFrom modern and minimal dining environments to warm, themed, and luxury concepts, each design is carefully crafted with attention to layout, lighting, materials, and customer flow. We focus on creating inviting ambiances that not only look visually stunning but also support smooth operations and maximize seating efficiency.\r\n\r\nExplore our restaurant interior designs to discover how creative space planning, mood lighting, and unique design elements come together to transform ordinary dining areas into memorable experiences that attract and retain customers.', NULL, 24, 58, NULL, 'published', '2026-04-19 03:54:59', '2026-04-19 05:31:44'),
(8, 'Residences', 'residences', 'A home is more than just a space—it’s a reflection of lifestyle, comfort, and personal identity. Our residences portfolio highlights a diverse range of thoughtfully designed living spaces, where aesthetics and functionality come together to create warm, inviting environments.\r\n\r\nFrom modern minimalist apartments to elegant and luxurious homes, each design is tailored to meet the unique needs of the residents. We focus on smart space planning, natural lighting, balanced color palettes, and carefully selected materials to ensure every corner feels both beautiful and practical.\r\n\r\nExplore our residential designs to see how we transform everyday living spaces into personalized sanctuaries—crafted for comfort, style, and a better quality of life.', NULL, 24, 28, NULL, 'published', '2026-04-19 03:58:48', '2026-04-19 03:58:48'),
(9, 'Bathroom Interior Design', 'bathroom-interior-design', NULL, NULL, 24, 28, 60, 'published', '2026-04-19 04:58:53', '2026-04-19 04:58:53'),
(10, 'Boy\'s Bedroom Interior', 'boys-bedroom-interior', '<p>A boy’s bedroom is a personal space designed for comfort, creativity, and growth. Our boy’s bedroom portfolio showcases smart, stylish, and functional designs that reflect personality while ensuring a practical living environment. From modern minimalist setups to vibrant, theme-based rooms, each design is carefully planned with optimized space usage, study-friendly layouts, storage solutions, and balanced color schemes. We focus on creating environments that support both relaxation and productivity. Explore our boy’s bedroom designs to see how thoughtful planning and creative interiors transform simple rooms into inspiring personal spaces tailored for study, rest, and lifestyle needs.</p>', '[{\"answer\": \"A professional designer helps you save time, avoid costly mistakes, and create a space that is both functional and visually appealing.\", \"question\": \"Why should I hire an interior designer?\"}, {\"answer\": \"Yes. We always design according to your budget and provide the best possible solution without compromising quality and aesthetics.\", \"question\": \"Do you work within budget?\"}, {\"answer\": \"It depends on the project size and complexity. Typically, a bedroom design can take 2–4 weeks, while full home interiors may take longer.\", \"question\": \"How long does an interior project take?\"}, {\"answer\": \"Yes, we provide 3D visualizations so you can clearly understand the final look before starting the work.\", \"question\": \"Do you provide 3D design before execution?\"}, {\"answer\": \"Absolutely. Every design we create is tailored to your lifestyle, preferences, and space requirements.\", \"question\": \"Can I customize the design according to my needs?\"}]', 24, 28, 45, 'published', '2026-04-19 05:17:26', '2026-04-27 11:00:52');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_images`
--

CREATE TABLE `portfolio_images` (
  `id` bigint UNSIGNED NOT NULL,
  `portfolio_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT '0',
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolio_images`
--

INSERT INTO `portfolio_images` (`id`, `portfolio_id`, `image_path`, `is_thumbnail`, `alt_text`, `created_at`, `updated_at`) VALUES
(15, 6, 'portfolios/ss-interior-design-gallery-0-phoxh.webp', 0, 'ss interior design perspective 1', '2026-04-19 02:47:45', '2026-04-19 03:56:39'),
(16, 6, 'portfolios/ss-interior-design-gallery-1-yr9ry.webp', 0, 'ss interior design perspective 2', '2026-04-19 02:47:46', '2026-04-19 03:56:39'),
(17, 6, 'portfolios/ss-interior-design-gallery-2-tpyco.webp', 0, 'ss interior design perspective 3', '2026-04-19 02:47:46', '2026-04-19 03:56:39'),
(18, 6, 'portfolios/ss-interior-design-gallery-3-exkz2.webp', 0, 'ss interior design perspective 4', '2026-04-19 02:47:47', '2026-04-19 03:56:39'),
(19, 6, 'portfolios/ss-interior-design-gallery-4-olwmi.webp', 0, 'ss interior design perspective 5', '2026-04-19 02:47:48', '2026-04-19 03:56:39'),
(20, 6, 'portfolios/ss-interior-design-gallery-5-i3rkp.webp', 0, 'ss interior design perspective 6', '2026-04-19 02:47:49', '2026-04-19 03:56:39'),
(21, 6, 'portfolios/ss-interior-design-gallery-6-ebkgx.webp', 0, 'ss interior design perspective 7', '2026-04-19 02:47:49', '2026-04-19 03:56:39'),
(22, 6, 'portfolios/ss-interior-design-gallery-7-xlv35.webp', 0, 'ss interior design perspective 8', '2026-04-19 02:47:50', '2026-04-19 03:56:39'),
(23, 6, 'portfolios/ss-interior-design-gallery-8-lck3p.webp', 0, 'ss interior design perspective 9', '2026-04-19 02:47:51', '2026-04-19 03:56:39'),
(24, 6, 'portfolios/ss-interior-design-gallery-9-ccsld.webp', 0, 'ss interior design perspective 10', '2026-04-19 02:47:51', '2026-04-19 03:56:39'),
(25, 6, 'portfolios/ss-interior-design-gallery-10-eijy4.webp', 0, 'ss interior design perspective 11', '2026-04-19 02:47:52', '2026-04-19 03:56:39'),
(26, 6, 'portfolios/ss-interior-design-gallery-11-v3hyd.webp', 0, 'ss interior design perspective 12', '2026-04-19 02:47:53', '2026-04-19 03:56:39'),
(27, 6, 'portfolios/ss-interior-design-gallery-12-gzt8p.webp', 0, 'ss interior design perspective 13', '2026-04-19 02:47:53', '2026-04-19 03:56:39'),
(28, 6, 'portfolios/ss-interior-design-gallery-13-zxivr.webp', 0, 'ss interior design perspective 14', '2026-04-19 02:47:54', '2026-04-19 03:56:39'),
(29, 7, 'portfolios/resturants-interior-design-gallery-0-i7wcx.webp', 0, 'Resturants interior design perspective 1', '2026-04-19 03:55:02', '2026-04-19 03:55:28'),
(30, 7, 'portfolios/resturants-interior-design-gallery-1-uisx1.webp', 0, 'Resturants interior design perspective 2', '2026-04-19 03:55:03', '2026-04-19 03:55:28'),
(31, 7, 'portfolios/resturants-interior-design-gallery-2-8g2un.webp', 0, 'Resturants interior design perspective 3', '2026-04-19 03:55:04', '2026-04-19 03:55:28'),
(32, 7, 'portfolios/resturants-interior-design-gallery-3-9hxs9.webp', 0, 'Resturants interior design perspective 4', '2026-04-19 03:55:05', '2026-04-19 03:55:28'),
(33, 7, 'portfolios/resturants-interior-design-gallery-4-djg9i.webp', 1, 'Resturants interior design perspective 5', '2026-04-19 03:55:05', '2026-04-19 03:55:28'),
(34, 7, 'portfolios/resturants-interior-design-gallery-5-5hfmu.webp', 0, 'Resturants interior design perspective 6', '2026-04-19 03:55:06', '2026-04-19 03:55:28'),
(35, 7, 'portfolios/resturants-interior-design-gallery-6-54kss.webp', 0, 'Resturants interior design perspective 7', '2026-04-19 03:55:07', '2026-04-19 03:55:28'),
(36, 6, 'portfolios/kitchen-interior-design-interior-design-gallery-0-ohrkf.webp', 1, 'Kitchen Interior Design interior design perspective 1', '2026-04-19 03:56:25', '2026-04-19 03:56:39'),
(37, 8, 'portfolios/residences-interior-design-gallery-0-me9lm.webp', 1, 'Residences interior design perspective 1', '2026-04-19 03:58:49', '2026-04-19 03:58:49'),
(38, 8, 'portfolios/residences-interior-design-gallery-1-xi2yq.webp', 0, 'Residences interior design perspective 2', '2026-04-19 03:58:50', '2026-04-19 03:58:50'),
(39, 8, 'portfolios/residences-interior-design-gallery-2-83jfk.webp', 0, 'Residences interior design perspective 3', '2026-04-19 03:58:51', '2026-04-19 03:58:51'),
(40, 8, 'portfolios/residences-interior-design-gallery-3-myvwf.webp', 0, 'Residences interior design perspective 4', '2026-04-19 03:58:52', '2026-04-19 03:58:52'),
(41, 8, 'portfolios/residences-interior-design-gallery-4-luzzh.webp', 0, 'Residences interior design perspective 5', '2026-04-19 03:58:53', '2026-04-19 03:58:53'),
(42, 9, 'portfolios/bathroom-interior-design-interior-design-gallery-0-sliwy.webp', 1, 'Bathroom Interior Design interior design perspective 1', '2026-04-19 04:58:59', '2026-04-19 04:58:59'),
(43, 10, 'portfolios/boys-bedroom-interior-interior-design-gallery-0-xkcpr.webp', 1, 'Boy\'s Bedroom Interior interior design perspective 1', '2026-04-19 05:17:28', '2026-04-19 05:17:28'),
(44, 10, 'portfolios/boys-bedroom-interior-interior-design-gallery-1-clr6f.webp', 0, 'Boy\'s Bedroom Interior interior design perspective 2', '2026-04-19 05:17:30', '2026-04-19 05:17:30'),
(45, 10, 'portfolios/boys-bedroom-interior-interior-design-gallery-2-kdvvy.webp', 0, 'Boy\'s Bedroom Interior interior design perspective 3', '2026-04-19 05:17:31', '2026-04-19 05:17:31'),
(46, 10, 'portfolios/boys-bedroom-interior-interior-design-gallery-3-0abms.webp', 0, 'Boy\'s Bedroom Interior interior design perspective 4', '2026-04-19 05:17:32', '2026-04-19 05:17:32'),
(47, 10, 'portfolios/boys-bedroom-interior-interior-design-gallery-4-r10nl.webp', 0, 'Boy\'s Bedroom Interior interior design perspective 5', '2026-04-19 05:17:33', '2026-04-19 05:17:33'),
(48, 10, 'portfolios/boys-bedroom-interior-interior-design-gallery-5-wgst9.webp', 0, 'Boy\'s Bedroom Interior interior design perspective 6', '2026-04-19 05:17:34', '2026-04-19 05:17:34'),
(49, 10, 'portfolios/boys-bedroom-interior-interior-design-gallery-6-slgmc.webp', 0, 'Boy\'s Bedroom Interior interior design perspective 7', '2026-04-19 05:17:35', '2026-04-19 05:17:35');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint UNSIGNED DEFAULT NULL,
  `child_category_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor_area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `category_id`, `sub_category_id`, `child_category_id`, `title`, `slug`, `description`, `location`, `client_name`, `completion_date`, `duration`, `floor_area`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 20, 'Mr. Shakil’s Residential Interior', 'mr-shakils-residential-interior', 'This residential interior project for Mr. Shakil in Ullapara was designed with a focus on comfort, functionality, and modern aesthetics. The overall design approach blends simplicity with elegance, creating a warm and welcoming living environment for everyday family life.\r\n\r\nNeutral color tones were used throughout the space to enhance brightness and visual balance, while carefully selected textures and materials add depth and character. Custom furniture solutions were designed to maximize space efficiency without compromising style. Proper lighting design was integrated to create a calm, cozy atmosphere and to highlight key interior elements.\r\n\r\nSpecial attention was given to ventilation, natural light flow, and practical storage solutions, ensuring the home remains both beautiful and livable. The result is a modern, well-organized residence that reflects the client’s lifestyle and personal taste.', 'Ullapara', 'Shakil', '2026-02-09', '1 Month', '1500 sq. ft', 'published', '2026-02-09 08:51:29', '2026-02-09 10:52:48');

-- --------------------------------------------------------

--
-- Table structure for table `project_images`
--

CREATE TABLE `project_images` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_images`
--

INSERT INTO `project_images` (`id`, `project_id`, `image_path`, `is_thumbnail`, `created_at`, `updated_at`) VALUES
(1, 1, 'projects/8ykNxzJHD1PekIlYYVvLsH8AYy9HR54sbVFf6TVU.jpg', 0, '2026-02-09 08:51:31', '2026-02-09 08:54:49'),
(2, 1, 'projects/PLLD5BLEAek2Q0Ai1xb4uc6vAuF2nODFKl7PsWdg.jpg', 1, '2026-02-09 08:51:31', '2026-02-09 08:54:49'),
(3, 1, 'projects/cz6WOVUif5Tsmf1qRxRuWd33whF6RxgWR7ZMhDV9.jpg', 0, '2026-02-09 11:46:24', '2026-02-09 11:46:24'),
(4, 1, 'projects/MJMsSzL5q7KlkOH72nlwoIK6klk7nF0wgwxYSjz0.jpg', 0, '2026-02-09 11:46:24', '2026-02-09 11:46:24'),
(5, 1, 'projects/6Pfpg1EqBZB13VDnduR5UCW4o8rbxVCRmZk7lQRz.jpg', 0, '2026-02-09 11:46:24', '2026-02-09 11:46:24');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `description` text,
  `faqs` json DEFAULT NULL,
  `sub_category_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `description`, `faqs`, `sub_category_id`, `status`, `created_at`, `updated_at`) VALUES
(6, '<h3><strong>Transform Your Space Into Something Meaningful</strong></h3><p>We design interiors that reflect your lifestyle, personality, and comfort. From concept to execution, every detail is thoughtfully crafted to create a home you truly love.</p><h2><strong>Our Core Services</strong></h2><h3><strong>1. Living Room Interior Design</strong></h3><p>Your living room is the heart of your home. We create elegant, functional, and welcoming spaces using:</p><ul><li>Modern furniture layouts</li><li>Lighting design for ambiance</li><li>Wall treatments (paint, panels, textures)</li><li>TV unit &amp; feature wall design</li></ul><h3><strong>2. Bedroom Interior Design</strong></h3><p>We design bedrooms that promote comfort, relaxation, and style.</p><ul><li>Master bedroom luxury concepts</li><li>Kids &amp; boys/girls themed rooms</li><li>Wardrobe &amp; storage optimization</li><li>Lighting and color harmony</li></ul><h3><strong>3. Kitchen Interior Design</strong></h3><p>Smart, efficient, and beautiful kitchen solutions:</p><ul><li>Modular kitchen layouts</li><li>Cabinet &amp; storage design</li><li>Space optimization</li><li>Durable and stylish materials</li></ul><h3><strong>4. Bathroom Interior Design</strong></h3><p>Clean, modern, and functional bathroom design:</p><ul><li>Shower zone planning</li><li>Tile &amp; color combination</li><li>Space-saving fittings</li><li>Lighting &amp; mirror design</li></ul><h3><strong>5. Custom Furniture Design</strong></h3><p>We design and build furniture tailored to your space:</p><ul><li>TV units</li><li>Wardrobes</li><li>Beds &amp; side tables</li><li>Cabinets and shelving</li></ul><h3><strong>6. Space Planning &amp; Layout Design</strong></h3><p>We maximize every inch of your home:</p><ul><li>Functional layouts</li><li>Proper zoning (living, dining, private areas)</li><li>Flow and movement optimization</li></ul><h3><strong>7. 3D Visualization &amp; Concept Design</strong></h3><p>See your home before execution:</p><ul><li>Realistic 3D views</li><li>Material and color preview</li><li>Design revisions before final work</li></ul><h3><strong>8. Turnkey Interior Solutions</strong></h3><p>We handle everything from start to finish:</p><ul><li>Design + Execution</li><li>Material sourcing</li><li>Project supervision</li><li>Final handover</li></ul><h2><strong>Why Choose Us</strong></h2><ul><li>Minimal &amp; Modern Design Approach</li><li>Budget-Friendly Solutions</li><li>High-Quality Materials</li><li>On-Time Project Delivery</li><li>Personalized Design Based on Your Lifestyle</li></ul><h2><strong>Our Process</strong></h2><ol><li><strong>Consultation</strong> – Understand your needs and vision</li><li><strong>Design Planning</strong> – Layout + concept development</li><li><strong>3D Visualization</strong> – Preview your space</li><li><strong>Execution</strong> – Professional implementation</li><li><strong>Handover</strong> – Ready-to-live space</li></ol><h2><strong>Let’s Design Your Dream Home</strong></h2><p>Ready to upgrade your home interior?</p><p>Contact us today for a consultation and bring your vision to life.</p>', '[{\"answer\": \"The cost depends on your space size, design style, materials, and customization level. We offer flexible solutions to match different budgets, from basic setups to premium interiors.\", \"question\": \"1. What is the cost of home interior design?\"}, {\"answer\": \"Yes, we offer complete turnkey interior solutions, including design, material sourcing, and full execution with supervision.\", \"question\": \"2. Do you provide both design and execution?\"}, {\"answer\": \"Project timelines vary depending on size and complexity. On average:\\n\\nSingle room: 7–15 days\\nFull apartment: 3–6 weeks\\n\\nWe always aim for timely delivery without compromising quality.\", \"question\": \"3. How long does a project take to complete?\"}, {\"answer\": \"Absolutely. Every design is personalized based on your lifestyle, needs, and taste. We don’t use copy-paste designs.\", \"question\": \"4. Can I customize the design according to my preference?\"}, {\"answer\": \"Yes, we provide realistic 3D visualizations so you can see how your space will look before execution begins.\", \"question\": \"5. Do you provide 3D design before starting work?\"}, {\"answer\": \"We use high-quality, durable materials such as HPL boards, MDF, plywood, and premium finishes depending on your budget and requirements.\", \"question\": \"6. What materials do you use?\"}, {\"answer\": \"Yes, we handle both small and large projects — from single room design to full home interiors.\", \"question\": \"7. Do you take small projects (single room)?\"}, {\"answer\": \"Simply contact us for a consultation. We’ll discuss your requirements, suggest ideas, and guide you through the entire process step by step.\", \"question\": \"8. How do I get started?\"}]', 36, 'published', '2026-04-29 09:01:21', '2026-04-29 10:38:59');

-- --------------------------------------------------------

--
-- Table structure for table `service_images`
--

CREATE TABLE `service_images` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` int NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT '0',
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_images`
--

INSERT INTO `service_images` (`id`, `service_id`, `image_path`, `is_thumbnail`, `alt_text`, `created_at`, `updated_at`) VALUES
(1, 6, 'services/gallery/service-gallery-0-uvjpe.webp', 1, 'Service perspective 1', '2026-04-29 09:01:59', '2026-04-29 09:10:20'),
(2, 6, 'services/gallery/service-gallery-0-0cdca.webp', 0, 'Service perspective 1', '2026-04-29 10:37:23', '2026-04-29 10:37:23'),
(3, 6, 'services/gallery/service-gallery-1-nedka.webp', 0, 'Service perspective 2', '2026-04-29 10:37:24', '2026-04-29 10:37:24'),
(5, 6, 'services/gallery/service-gallery-3-qpncf.webp', 0, 'Service perspective 4', '2026-04-29 10:37:26', '2026-04-29 10:37:26');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1woYEapXtf4iEqAyusrVasS6vATUUIdDocmH98f7', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidEZENGdGdUtIaWhhZVR2N3VVclpGTnhzd1lMdnBkTE9XV3RpYWNUQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432026),
('2jzyW9BeDjgpiFw4uSgcOWnH4vyOo8GGD4YbLwOg', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWFRb3U5Z0xXb1o0UWZ2RmxRWVk2Y1FXTU9rSzJCTXZSa3FZRFpSZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432026),
('9xhpU1buTveWileL64D2tAYEx7YGB0UK7Yivg1DC', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMXVHTHNGOEpZdE5tWTdncmZRQ29xWElvYzBwa2xXU3c1OFdZUWRhUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432024),
('AD5MkbwFhKVsBhTklM0GoIAXq3jHCOPobLlb7vPQ', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaHlDM0RuMXpQU2JCUldwVkw4Z0ZEbHAzUko0MGJaRlV3WWVib1NEQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjM6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL2Zvb3RlciI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432026),
('cVdNNcZIIbID9oBHuvOMIBuJLNr57vEfnxwdsAW1', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUlBXM0pweGxVS29rUUVnNXJuUXljTmljdU9aYTljS2g5WDFDRk5hNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjM6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL2Zvb3RlciI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432024),
('dmV4cthNtijBiJYm725BmsEtxjljMJzfLywK0ytI', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjFoNldiRXVYdnJUV2Q4NFlzRWRKUmw1VGVRcGRFYnJQV01KQTA3YyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432026),
('ipEzITXHIXsmf6pckfOVybLhiAwIRXbw1srtX5dC', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjM5V2FGanRFbDRmNHRXbDJEQVR6VGZkNG5GT3BWU2RCOEc0Z0dzNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432024),
('mziEmjSsHpMtCnLjxunvgPdKFogh47dXfF0TEk4y', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWdwY24zYTR6aHJnWGxLaEsycUxpZ051UG9IQW5oRG4xNDl1RmZpdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NlcnZpY2VzL2hvbWUtaW50ZXJpb3ItZGVzaWduIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778432024),
('OhgfmHEbwUJ7E1LbESZFOTsDx24bM6FcjF8slO9K', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidk9pb0pFTGFHeWpZUEhNbXhrdmJUa3VKemV1NzJLR3Mzc2M2N0VWZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL2NhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778432026),
('pB7c6sawlDxhEQfsbzU9Z1Df2XiAB7rFXDuMyOUP', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieGVZTGhLMXpjVzY1WVlZeERtb25SOTdXd3REMmcwV2toTzZoUXNVVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NvY2lhbC1saW5rcyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432024),
('Q83EiGvb4YvoKFT2IrJElxz0nv4N6UNxaKKN9pPD', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidTh1aTBmSFBYM25ISlJKMmxhZWRHUHh5ZG1rVEZnTmQ3cm9meDVSVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzE6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL2FkbWluLXByb2plY3RzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778432026),
('QgQiRQ1aZiWqT3ePdC2COMWKMNBS4kDuzYCokrQb', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnJSWFdYcXMxalhLWXNnMVdPcmJkSktWODF2M0lRSld0NDVxWlpIYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432024),
('qwzMRm6M1tDeLSTNLCR9DzVQIeAKeTgHEdLhRpYX', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOElxZFY1eExGdk1ZS0xjSHZacHNuOUNtWkMzaGJ0SWYySGZiYW5wZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjU6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NlcnZpY2VzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778432026),
('rml0QYOrsIenO64gSNefIIpyxdkm2mqzuAZYdxeO', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicWNUYXZoRnhDdnd3SDVXVTI5eThOdnpUbVVzT1UyZVp6WnRjeFloVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432024),
('vyk0RcEPI55PAJARBPadrYjiZskVOcmg0K98Qxru', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFVjN3BWd3R2djJzb2UzbHlBejkzMmpDcFVQczBWME1kTllURnAwMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzE6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL2FkbWluLXByb2plY3RzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778432025),
('Wpgzko4eOTvPjKxDTFUIjc4ciAVXwrMlT5azmFHW', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXE2UEU2dHNveVFNb21xa0hMVXNGRjdUeHdVS1o5eTgxclJkNTRZciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432026),
('X5n6pyfbDSqSJnAcW4Jc7GAv40zl2aIDnW3jKGyQ', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWmU5R29lSjR5TldUWlJCcjFnZGlyRW1RemptOHFZSDdweXFvSWpsNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL2NhdGVnb3JpZXMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778432024),
('xFvVkAn87AfBTXo0UfoyHY9lMh5sQ0nzLMxADC8B', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQlIyQWJ0YmpVbDZrb2JpYzhzakI1QW5iQTQyOVJMVlg2MDExbm9OZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NvY2lhbC1saW5rcyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432026),
('xkzJ97O8iYqDL8ngmh3OFc0top7a8I79bY3yzW8J', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibkdCODF0RFBIcUlGUXh6eHJmeHQ3N2tFdHZhdGNuRzBMS0E2TjU1TiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjU6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NlcnZpY2VzIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778432024),
('yvSz1t2kFMtPHbl1dT4spC7JaBdFCfK4R1horG7X', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQkVidW5XVFpadENFMnRROFkxZXY0Qkp2N1NaMUg1UUtwSGt0cFNMQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NlcnZpY2VzL2hvbWUtaW50ZXJpb3ItZGVzaWduIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778432024),
('z2Wi8o1W1HQXTqdOW2qOwPtIBP1uzLZ4VCMsH0Sd', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3drWGJacUw4Z25iSXJRUGU1V1d1UUo1amRrS2hSRjBuRjBzMHJZaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432026),
('ZXZ8LoM7VjpCKEACkDhRHZZ3oaIUSJBz9qoIqJuK', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiblZWclhLR0RCdHZwd2dMRXlHaVF2NGFOQzdna3hsbkNMSGcza0lNeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlbWl1bV90b3VjaC9wcmVtaXVtLXRvdWNoLWJhY2tlbmQvYXBpL3NpdGUtaW5mbyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778432026);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `short_description` text,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `map_embed_url` text,
  `map_url` varchar(255) DEFAULT NULL,
  `facebook_page_url` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `project_header_bg` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `tagline`, `short_description`, `phone`, `email`, `address`, `map_embed_url`, `map_url`, `facebook_page_url`, `logo`, `project_header_bg`, `updated_at`) VALUES
(1, 'Premium Touch Interior Decor Studio', 'Your Personal Touch with \"Premium Touch\"', 'Premium Touch Interior Decor Studio crafts stylish, elegant spaces tailored to your taste, bringing your dream interiors to life beautifully.', '+8801712345678', 'info@premiumtouchbd.com', 'House 12, Road 5, Dhanmondi, Dhaka-1209, Bangladesh', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.174495439541!2d90.4085174744503!3d23.812393278628186!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c7b14e526f15%3A0x60c45651ab276b26!2sPremium%20Touch%20-%20Interior%20Decor%20Studio%20%7C%20Best%20Interior%20Designer%20In%20Bangladesh%20%7C%20Best%20Architect%20In%20Bangladesh!5e0!3m2!1sen!2sbd!4v1766505536718!5m2!1sen!2sbd', 'https://maps.app.goo.gl/mPUGKNHc74XwAtp39', 'https://www.facebook.com/premiumtouchinteriordecorstudio', 'logo.jpg', 'project_header_1770657888.jpg', '2026-02-09 11:24:48');

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` int NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `position` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `social_links`
--

INSERT INTO `social_links` (`id`, `name`, `icon`, `url`, `status`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Facebook', 'fab fa-facebook-f', 'https://facebook.com', 1, 1, '2025-12-18 15:48:50', NULL),
(2, 'Instagram', 'fab fa-instagram', 'https://instagram.com', 1, 2, '2025-12-18 15:48:50', NULL),
(3, 'LinkedIn', 'fab fa-linkedin-in', 'https://linkedin.com', 1, 3, '2025-12-18 15:48:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `profile_picture`) VALUES
(1, 'Md. Sajidul Islam', 'sajidulcse013@gmail.com', NULL, '$2y$12$dLpI2nylAIyTC5ex7lYG5.aGQpNb.QsU4.oKFwjo3KsfXX7cV2BCK', NULL, '2026-01-16 04:25:25', '2026-04-18 23:53:35', 'profiles/MeuvT0WMl9catsRvHShNqUbGQ5i8DL3fPBNQ1WCt.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blogs_slug_unique` (`slug`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_categories_slug_unique` (`slug`);

--
-- Indexes for table `blog_images`
--
ALTER TABLE `blog_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_images_blog_id_foreign` (`blog_id`);

--
-- Indexes for table `blog_reactions`
--
ALTER TABLE `blog_reactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_reactions_blog_id_foreign` (`blog_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_blog_id_foreign` (`blog_id`),
  ADD KEY `comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `footer_sections`
--
ALTER TABLE `footer_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolios_slug_unique` (`slug`),
  ADD KEY `portfolios_category_id_foreign` (`category_id`),
  ADD KEY `portfolios_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `portfolios_child_category_id_foreign` (`child_category_id`);

--
-- Indexes for table `portfolio_images`
--
ALTER TABLE `portfolio_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `portfolio_images_portfolio_id_foreign` (`portfolio_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_slug_unique` (`slug`),
  ADD KEY `projects_category_id_foreign` (`category_id`),
  ADD KEY `projects_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `projects_child_category_id_foreign` (`child_category_id`);

--
-- Indexes for table `project_images`
--
ALTER TABLE `project_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_images_project_id_foreign` (`project_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_images`
--
ALTER TABLE `service_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_images_service_id_foreign` (`service_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blog_images`
--
ALTER TABLE `blog_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `blog_reactions`
--
ALTER TABLE `blog_reactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `footer_sections`
--
ALTER TABLE `footer_sections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `portfolio_images`
--
ALTER TABLE `portfolio_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_images`
--
ALTER TABLE `project_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_images`
--
ALTER TABLE `service_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_images`
--
ALTER TABLE `blog_images`
  ADD CONSTRAINT `blog_images_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_reactions`
--
ALTER TABLE `blog_reactions`
  ADD CONSTRAINT `blog_reactions_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD CONSTRAINT `portfolios_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `portfolios_child_category_id_foreign` FOREIGN KEY (`child_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `portfolios_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `portfolio_images`
--
ALTER TABLE `portfolio_images`
  ADD CONSTRAINT `portfolio_images_portfolio_id_foreign` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_child_category_id_foreign` FOREIGN KEY (`child_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `project_images`
--
ALTER TABLE `project_images`
  ADD CONSTRAINT `project_images_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_images`
--
ALTER TABLE `service_images`
  ADD CONSTRAINT `service_images_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
