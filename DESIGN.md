# Cannypedia Design Direction

## Design read

Reading this as: a digital top-up storefront for Indonesian game and mobile-service customers, using a clear dark slate storefront language with amber selection cues. ENERGY 2 / RHYTHM 2 / MOTION 1.

## Identity and palette

- Dark mode is the fixed storefront theme because game and digital-credit catalogs are routinely browsed as media-rich, low-glare interfaces. It is not a user-selectable theme.
- Background: `bg-slate-900`; product and content surfaces: `bg-slate-800` with `border-slate-700`.
- Amber (`bg-amber-500`, dark text) is reserved for purchase actions and the selected denomination, so the checkout decision stays visually distinct.
- Status colors carry meaning only: `emerald-500` for success, `amber-400` for pending, and `rose-500` for failure.
- Slate neutrals carry most of the interface. Status colors appear only beside their matching transaction state.
- Use Plus Jakarta Sans with a system sans-serif fallback for legible Indonesian labels and price grids. Every displayed amount uses tabular numerals for stable comparison.

## Composition and interaction

- The home page puts search and category filters directly above the product catalog because discovery is the main task.
- Product ordering uses a wide input-and-denomination column beside a compact order summary on desktop; the summary follows the form on narrow screens.
- Payment and invoice pages narrow into a receipt-like column so invoice identity and transaction state are easy to scan.
- Product cards use a quiet surface and a consistent text-first layout. The selected denomination gets a single amber outline and fill; no fabricated product artwork is shown.
- The identity motif is an amber index mark repeated at the selected denomination and primary purchase action. It ties catalog selection to checkout without decorating unrelated content.
- Spacing stays compact within a task and opens between the four order decisions, so the sequence remains scannable without making each section feel identical.
- Motion is limited to brief hover, focus, and selection transitions. Reduced-motion preferences remove non-essential transitions.
- Navigation exposes only the catalog and the implemented transaction lookup. All actions use native links, buttons, and labeled form controls.

## Content boundaries

- This is a local simulation. Payment pages must clearly state that no real payment or provider API is used.
- Seed products and prices are sample data for testing, not live offers.
- Product art is optional. When an image is unavailable, use a text fallback instead of inventing a logo or image.
