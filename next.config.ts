import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  webpack: (config, { isServer }) => {
    return config;
  },
  // CloudLinux LVE limits process spawning; skip the type-check worker
  // and use worker_threads + a single CPU during build.
  typescript: { ignoreBuildErrors: true },
  experimental: {
    cpus: 1,
    workerThreads: true,
  },
};

export default nextConfig;
