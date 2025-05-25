import { isAxiosError } from 'axios'
import type { Path, SubmissionContext } from 'vee-validate'

export function handleValidationError<T extends Record<string, any>>(
  error: unknown,
  action: SubmissionContext<T>,
): Partial<Record<keyof T, string>> | null {
  if (isAxiosError(error) && error.status === 422) {
    if (error.response?.data) {
      const validationErrors: Record<string, string[]> | undefined = error.response.data.errors

      if (!validationErrors) return null

      for (const [key, value] of Object.entries(validationErrors)) {
        console.log(key, value)
        action.setFieldError(key as Path<T>, value)
      }
    }
  }
  return null
}
