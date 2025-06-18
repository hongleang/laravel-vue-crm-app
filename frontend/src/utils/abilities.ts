import type { LoggedInUser } from '@/types/model'
import { AbilityBuilder, createMongoAbility } from '@casl/ability'

export const ability = createMongoAbility([])

export function updateAbilityFor(user: LoggedInUser) {
  const { can, rules } = new AbilityBuilder(createMongoAbility)
  user.abilities.forEach((ability) => {
    can(ability, '*')
  })

  ability.update(rules)
}
