import type { LoggedInUser } from '@/stores/auth'
import { AbilityBuilder, createMongoAbility } from '@casl/ability'

export const ability = createMongoAbility([])

export function updateAbilityFor(user: LoggedInUser) {
  const { can, rules } = new AbilityBuilder(createMongoAbility)
  user.abilities.forEach((ability) => {
    can(ability.action, ability.subject)
  })

  ability.update(rules)
}
