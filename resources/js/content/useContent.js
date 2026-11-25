import { usePage } from '@inertiajs/react'

/** All site copy, images and lists, shared by Laravel on every Inertia page. */
export const useContent = () => usePage().props.content
